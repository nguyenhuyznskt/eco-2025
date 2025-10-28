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

class ProductController extends Controller
{
    public function __construct(
        protected ProductCrudService $svc,
        protected SoftDeleteService $trash,
        protected CategoryService $categorySvc
    ) {}

    public function index(Request $request): View
    {
        $q = Product::query()
            ->withCount('variants')
            ->when($request->keyword, function($qq,$kw){
                $qq->where('name','like',"%{$kw}%")->orWhere('slug','like',"%{$kw}%");
            })
            ->orderByDesc('id');

        $data = $q->paginate(12)->withQueryString();

        return view('admin.products.index', compact('data'));
    }

    public function create(): View
    {
        $variationAttributes = Attribute::with(['values' => fn($q)=>$q->orderBy('sort_order')])
            ->where('is_variation', 1)
            ->orderBy('name')
            ->get();
    
        $categories = $this->categorySvc->getLeafCategories();
        $vendors    = Vendor::orderBy('shop_name')->get(['id','shop_name']);
    
        return view('admin.products.create', [
            'variationAttributes' => $variationAttributes,
            'categories' => $categories,
            'vendors' => $vendors,
        ]);
    }

    public function store(Request $request, ProductCrudService $svc): RedirectResponse
{
    // validate sản phẩm cơ bản
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

    // attribute + variant từ form
    $attrMap  = $request->input('attribute_values', []); // [attribute_id => [value_id,...]]
    $variants = $request->input('combinations', []);    // JS generate

    $svc->create($productData, $attrMap, $variants);

    return redirect()->route('admin.product.index')->with('success','Đã tạo sản phẩm và biến thể.');
}

public function edit(Product $product): View
{
    $product->load('variants');

    $variationAttributes = Attribute::with(['values' => fn($q)=>$q->orderBy('sort_order')])
                            ->where('is_variation',1)->orderBy('name')->get();

    // 🔹 Thêm 2 dòng này:
    $categories = $this->categorySvc->getLeafCategories();
    $vendors    = Vendor::orderBy('shop_name')->get(['id','shop_name']);

    // map value hiện tại ở cấp product
    $selected = DB::table('product_attribute_values')
                ->where('product_id',$product->id)
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
        'slug' => 'nullable|string|max:255|unique:products,slug,'.$product->id,
        'price' => 'nullable|numeric|min:0',
        'compare_price' => 'nullable|numeric|min:0',
        'short_description' => 'nullable|string',
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'is_featured' => 'nullable|boolean',
    ]);

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
        return back()->with('success','Đã xóa mềm');
    }

    // BULK SOFT DELETE
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->trash->bulkDelete(Product::class, $ids);
        return back()->with('success',"Đã xóa mềm {$count} sản phẩm");
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
        return back()->with('success','Đã khôi phục');
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
        return back()->with('success','Đã xóa vĩnh viễn');
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
