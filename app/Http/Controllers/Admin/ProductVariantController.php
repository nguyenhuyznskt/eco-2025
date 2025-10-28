<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'combinations' => 'required|array|min:1',
            'combinations.*.value_ids' => 'required|array|min:1',
            'combinations.*.sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'combinations.*.price' => 'nullable|numeric|min:0',
            'combinations.*.compare_price' => 'nullable|numeric|min:0',
            'combinations.*.is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function() use ($product, $data) {
            foreach ($data['combinations'] as $combo) {
                $valueIds = array_map('intval', Arr::get($combo, 'value_ids', []));
                sort($valueIds);
                $variant = ProductVariant::create([
                    'product_id'    => $product->id,
                    'sku'           => Arr::get($combo, 'sku'),
                    'attributes'    => ['value_ids' => $valueIds],
                    'price'         => Arr::get($combo, 'price'),
                    'compare_price' => Arr::get($combo, 'compare_price'),
                    'is_active'     => Arr::get($combo, 'is_active', 1),
                ]);
                $pairs = DB::table('attribute_values')->whereIn('id',$valueIds)->get(['id as attribute_value_id','attribute_id']);
                $rows = [];
                foreach ($pairs as $p) $rows[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant->id,
                    'attribute_id'       => (int)$p->attribute_id,
                    'attribute_value_id' => (int)$p->attribute_value_id,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
                if ($rows) DB::table('product_attribute_values')->insert($rows);
            }
        });

        return back()->with('success','Đã thêm biến thể');
    }

    public function update(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $payload = $request->validate([
            'sku'           => 'nullable|string|max:255|unique:product_variants,sku,'.$variant->id,
            'price'         => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'is_active'     => 'nullable|boolean',
        ]);
        $variant->update($payload);
        return back()->with('success','Đã cập nhật biến thể');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        DB::transaction(function() use ($variant) {
            DB::table('product_attribute_values')->where('product_variant_id',$variant->id)->delete();
            $variant->delete();
        });
        return back()->with('success','Đã xóa biến thể');
    }
}
