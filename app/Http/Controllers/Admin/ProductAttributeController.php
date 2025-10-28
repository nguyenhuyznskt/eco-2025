<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Services\Admin\ProductAttributeService;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function __construct(protected ProductAttributeService $svc) {}

    public function index()
    {
        $data = $this->svc->paginate();
        return view('admin.product_attributes.index', compact('data'));
    }

    public function create()
    {
        $products = Product::pluck('name','id');
        $values = AttributeValue::with('attribute')->get();
        return view('admin.product_attributes.create', compact('products','values'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_value_ids' => 'required|array|min:1',
            'attribute_value_ids.*' => 'exists:attribute_values,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $this->svc->assign($product, $validated['attribute_value_ids']);

        return redirect()->route('admin.product_attribute.index')->with('success','Gán thuộc tính thành công!');
    }

    public function destroy(Product $product, AttributeValue $attributeValue)
    {
        $this->svc->detach($product, $attributeValue);
        return redirect()->route('admin.product_attribute.index')->with('success','Đã gỡ thuộc tính khỏi sản phẩm!');
    }
}
