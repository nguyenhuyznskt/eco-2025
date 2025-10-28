<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductCreateService
{
    /**
     * $payload:
     *  - product: [name, slug, description, price, compare_price, is_active, ...]
     *  - attribute_values: [attribute_id => [value_id, ...]]  // giá trị dùng để sinh variant & filter
     *  - combinations: [
     *      ['value_ids'=>[...], 'sku'=>..., 'price'=>..., 'compare_price'=>..., 'is_active'=>1],
     *      ...
     *    ]
     */
    public function createWithVariants(array $payload): Product
    {
        return DB::transaction(function () use ($payload) {

            // 1) Tạo product
            $productData = Arr::get($payload, 'product', []);
            /** @var Product $product */
            $product = Product::create($productData);

            // 2) Gắn attribute_values ở cấp product (pivot product_attribute_values, product_variant_id NULL)
            $map = Arr::get($payload, 'attribute_values', []);
            $rows = [];
            foreach ($map as $attributeId => $valueIds) {
                foreach (Arr::wrap($valueIds) as $valueId) {
                    $rows[] = [
                        'product_id'         => $product->id,
                        'product_variant_id' => null,
                        'attribute_id'       => (int) $attributeId,
                        'attribute_value_id' => (int) $valueId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];
                }
            }
            if ($rows) {
                DB::table('product_attribute_values')->insert($rows);
            }

            // 3) Tạo các biến thể từ combinations
            $combos = Arr::get($payload, 'combinations', []);
            foreach ($combos as $combo) {
                $valueIds = array_map('intval', Arr::get($combo, 'value_ids', []));
                if (empty($valueIds)) continue;

                sort($valueIds); // chuẩn hóa để tránh trùng
                $variant = ProductVariant::create([
                    'product_id'    => $product->id,
                    'sku'           => Arr::get($combo, 'sku'),
                    'attributes'    => ['value_ids' => $valueIds], // JSON theo schema DB của mày
                    'price'         => Arr::get($combo, 'price'),
                    'compare_price' => Arr::get($combo, 'compare_price'),
                    'weight'        => Arr::get($combo, 'weight'),
                    'length'        => Arr::get($combo, 'length'),
                    'width'         => Arr::get($combo, 'width'),
                    'height'        => Arr::get($combo, 'height'),
                    'is_active'     => Arr::get($combo, 'is_active', 1),
                ]);

                // map value_ids -> pivot cho đúng variant
                $pivotRows = [];
                if ($valueIds) {
                    $pairs = DB::table('attribute_values')
                        ->whereIn('id', $valueIds)
                        ->get(['id as attribute_value_id', 'attribute_id']);

                    foreach ($pairs as $p) {
                        $pivotRows[] = [
                            'product_id'         => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_id'       => (int) $p->attribute_id,
                            'attribute_value_id' => (int) $p->attribute_value_id,
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ];
                    }
                }
                if ($pivotRows) {
                    DB::table('product_attribute_values')->insert($pivotRows);
                }
            }

            return $product;
        });
    }
}
