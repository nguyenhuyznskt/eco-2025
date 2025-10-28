<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Admin\ProductVariantService;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    protected $variantService;

    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variants = $this->variantService->getByProductId($productId);

        return view('admin.product_variant.index', compact('product', 'variants'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
        ]);

        $this->variantService->create([
            'product_id' => $productId,
            'sku' => $request->sku,
            'attributes' => $request->attributes ? json_encode($request->attributes) : null,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'is_active' => $request->has('is_active'),
        ]);
    //     $product = $svc->createProductWithVariants($data);

    // return redirect()->route('indexProduct')
    //     ->with('success', 'Tạo sản phẩm và biến thể thành công!');

        return back()->with('success', 'Thêm biến thể thành công!');
    }

    public function update(Request $request, $productId, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);

        $variant->update([
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Cập nhật biến thể thành công!');
    }

    public function destroy($productId, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->delete();

        return back()->with('success', 'Xoá biến thể thành công!');
    }
}
