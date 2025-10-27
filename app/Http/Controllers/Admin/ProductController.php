<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Category;
use App\Services\Admin\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Danh sách sản phẩm (có search + filter + paginate)
     */
    public function index(Request $request)
    {
        $filters = [
            'search'      => $request->input('search'),
            'is_active'   => $request->input('is_active'),
            'category_id' => $request->input('category_id'),
            'order_by'    => $request->input('order_by'),
        ];

        $products = $this->productService->paginate($filters, 10);
        $categories = Categories::all();

        return view('admin.products.index', compact('products', 'categories', 'filters'));
    }

    /**
     * Form tạo sản phẩm mới
     */
    public function create()
    {
        $categories = Categories::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'category_id'       => 'required|integer|exists:categories,id',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'meta'              => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = $request->file('image');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $this->productService->create($validated, $image);

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Form sửa sản phẩm
     */
    public function edit(int $id)
    {
        $product = $this->productService->find($id);
        $categories = Categories::all();

        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Không tìm thấy sản phẩm!');
        }

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'category_id'       => 'required|integer|exists:categories,id',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'meta'              => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = $request->file('image');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $this->productService->update($id, $validated, $image);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy(int $id)
    {
        $this->productService->delete($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm thành công!');
    }
}
