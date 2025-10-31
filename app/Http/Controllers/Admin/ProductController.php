<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\CategoryService;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Admin\ProductCrudService;
use App\Services\Admin\SoftDeleteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Vendor;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function __construct(
        protected ProductCrudService $svc,
        protected SoftDeleteService $trash,
        protected CategoryService $categorySvc
    ) {}

    public function index(Request $request): View
    {
        // Lấy toàn bộ danh mục
        $allCategories = Categories::select('id', 'parent_id', 'name')->get();
    
        // ✅ Hàm đệ quy lấy tất cả ID con
        $getAllChildCategoryIds = function ($all, $parentId) use (&$getAllChildCategoryIds) {
            $ids = [$parentId];
            foreach ($all->where('parent_id', $parentId) as $child) {
                $ids = array_merge($ids, $getAllChildCategoryIds($all, $child->id));
            }
            return $ids;
        };
    
        // ✅ Truy vấn sản phẩm
        $q = Product::query()
            ->with(['images']) // 🟢 Thêm dòng này để load ảnh
            ->withCount('variants')
            ->when($request->keyword, fn($qq, $kw) =>
                $qq->where(fn($sub) =>
                    $sub->where('name', 'like', "%{$kw}%")
                        ->orWhere('slug', 'like', "%{$kw}%")
                )
            )
            ->when($request->category_id, function ($qq, $catId) use ($allCategories, $getAllChildCategoryIds) {
                $childIds = $getAllChildCategoryIds($allCategories, $catId);
                $qq->whereIn('category_id', $childIds);
            })
            ->when($request->vendor_id, fn($qq, $vendor) =>
                $qq->where('vendor_id', $vendor)
            )
            ->when($request->status === 'active', fn($qq) => $qq->where('is_active', 1))
            ->when($request->status === 'inactive', fn($qq) => $qq->where('is_active', 0))
            ->when($request->featured === '1', fn($qq) => $qq->where('is_featured', 1))
            ->when($request->min_price, fn($qq, $min) => $qq->where('price', '>=', $min))
            ->when($request->max_price, fn($qq, $max) => $qq->where('price', '<=', $max))
            ->orderByDesc('id');
    
        $data = $q->paginate(12)->withQueryString();
    
        $categories = $allCategories;
        $vendors = Vendor::orderBy('shop_name')->get(['id', 'shop_name']);
    
        return view('admin.products.index', compact('data', 'categories', 'vendors'));
    }
    
    




    public function create(): View
    {
        $variationAttributes = Attribute::with(['values' => fn($q) => $q->orderBy('sort_order')])
            ->where('is_variation', 1)
            ->orderBy('name')
            ->get();

        $categories = $this->categorySvc->getLeafCategories();
        $vendors    = Vendor::orderBy('shop_name')->get(['id', 'shop_name']);

        return view('admin.products.create', [
            'variationAttributes' => $variationAttributes,
            'categories' => $categories,
            'vendors' => $vendors,
        ]);
    }

    public function store(Request $request, ProductCrudService $svc): RedirectResponse
    {
        // 1️⃣ Validate sản phẩm cơ bản
        $productData = $request->validate([
            'vendor_id'      => 'required|exists:vendors,id',
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|max:255',
            'slug'           => 'nullable|max:255|unique:products,slug',
            'price'          => 'nullable|numeric|min:0',
            'compare_price'  => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string',
            'description'    => 'nullable|string',
            'is_active'      => 'nullable|boolean',
            'is_featured'    => 'nullable|boolean',
        ]);

        $productData['is_active']   = $request->boolean('is_active');
        $productData['is_featured'] = $request->boolean('is_featured');

        // ảnh đại diện (nếu có)
        if ($request->hasFile('image')) {
            $productData['image'] = $request->file('image')->store('products', 'public');
        }

        // 🔹 GOM THÔNG TIN KỸ THUẬT
        $keys = $request->input('spec_key', []);     // mảng các key
        $vals = $request->input('spec_value', []);   // mảng các value
        $meta = [];
        foreach ($keys as $i => $k) {
            $k = trim((string) $k);
            $v = trim((string) ($vals[$i] ?? ''));
            if ($k !== '') {
                $meta[$k] = $v; // map để query/search dễ
            }
        }
        $productData['meta'] = $meta;

        // attribute + variant
        $attrMap  = $request->input('attribute_values', []);
        $variants = $request->input('combinations', []);

        // ảnh biến thể (nếu có input file per-variant)
        if ($request->has('combinations')) {
            foreach ($request->file('combinations', []) ?? [] as $idx => $files) {
                if (!empty($files['image'])) {
                    $variants[$idx]['image'] = $files['image']->store('variants', 'public');
                }
            }
        }
        
        

        $svc->create($productData, $attrMap, $variants);

        return redirect()->route('admin.product.index')->with('success', 'Đã tạo sản phẩm và biến thể.');
    }
   


    public function edit(Product $product): View
    {
        $product->load('variants');

        $variationAttributes = Attribute::with(['values' => fn($q) => $q->orderBy('sort_order')])
            ->where('is_variation', 1)->orderBy('name')->get();

        // 🔹 Thêm 2 dòng này:
        $categories = $this->categorySvc->getLeafCategories();
        $vendors    = Vendor::orderBy('shop_name')->get(['id', 'shop_name']);

        // map value hiện tại ở cấp product
        $selected = DB::table('product_attribute_values')
            ->where('product_id', $product->id)
            ->whereNull('product_variant_id')
            ->get();
        $selectedMap = [];
        foreach ($selected as $row) {
            $selectedMap[$row->attribute_id][] = $row->attribute_value_id;
        }

        return view('admin.products.edit', [
            'mode' => 'edit',
            'product' => $product,
            'variationAttributes' => $variationAttributes,
            'selectedMap' => $selectedMap,
            'variants' => $product->variants()->latest()->get(),
            // 🔹 Và truyền xuống view:
            'categories' => $categories,
            'vendors' => $vendors,
        ]);
    }


    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // ảnh đại diện (nếu có cập nhật)
        if ($request->hasFile('image')) {
            // 🔹 Xóa ảnh cũ (nếu có)
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        
            // 🔹 Lưu ảnh mới
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        

        // 🔹 GOM THÔNG TIN KỸ THUẬT
        $keys = $request->input('spec_key', []);
        $vals = $request->input('spec_value', []);
        $meta = [];
        foreach ($keys as $i => $k) {
            $k = trim((string) $k);
            $v = trim((string) ($vals[$i] ?? ''));
            if ($k !== '') $meta[$k] = $v;
        }
        $validated['meta'] = $meta;

        $attrMap  = $request->input('attribute_values', []);
        $combos   = $request->input('combinations', []);
        $replace  = $request->boolean('replace_variants', false);

        $this->svc->update($product, $validated, $attrMap, $combos, $replace);

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }


    // SOFT DELETE 1
    public function destroy(Product $product): RedirectResponse
    {
        $this->trash->deleteOne($product);
        return back()->with('success', 'Đã xóa mềm');
    }

    // BULK SOFT DELETE
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->trash->bulkDelete(Product::class, $ids);
        return back()->with('success', "Đã xóa mềm {$count} sản phẩm");
    }

    // LIST TRASH
    public function trashed(): View
    {
        $data = $this->trash->trashed(Product::class);
        return view('admin.products.trashed', compact('data'));
    }

    public function restore($id): RedirectResponse
    {
        $this->trash->restoreById(Product::class, $id);
        return back()->with('success', 'Đã khôi phục');
    }

    public function bulkRestore(Request $request): RedirectResponse
    {
        $ids = Arr::wrap($request->input('ids', []));
        $restored = $this->trash->bulkRestore(Product::class, $ids);
        return back()->with('success', "Đã khôi phục {$restored} sản phẩm");
    }

    public function forceDelete($id): RedirectResponse
    {
        $this->trash->forceDeleteById(Product::class, $id);
        return back()->with('success', 'Đã xóa vĩnh viễn');
    }

    public function bulkForceDelete(Request $request): RedirectResponse
    {
        $ids = Arr::wrap($request->input('ids', []));
        $deleted = $this->trash->bulkForceDelete(Product::class, $ids);
        return back()->with('success', "Đã xóa vĩnh viễn {$deleted} sản phẩm");
    }

    public function forceDeleteAll(): RedirectResponse
    {
        $deleted = $this->trash->forceDeleteAll(Product::class);
        return back()->with('success', "Đã xóa vĩnh viễn toàn bộ ({$deleted})");
    }
}
