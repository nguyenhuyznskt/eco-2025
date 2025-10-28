<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductVariantService
{
    /**
     * $payload:
     *  [
     *    'combinations' => [
     *       ['value_ids' => [12,34], 'sku' => 'SKU-RED-M', 'price' => 199000, 'compare_price' => null, 'is_active' => 1],
     *       ...
     *    ]
     *  ]
     * => value_ids là mảng id của attribute_values tạo nên variant.
     */
    public function createMany(Product $product, array $payload): int
    {
        $combos = Arr::get($payload, 'combinations', []);
        $count  = 0;

        DB::transaction(function() use ($product, $combos, &$count) {
            foreach ($combos as $combo) {
                $valueIds = array_map('intval', Arr::get($combo, 'value_ids', []));
                if (empty($valueIds)) continue;

                // attributes JSON để tiện hiển thị (nhớ chuẩn hoá sắp xếp cho duy nhất)
                sort($valueIds);
                $attrJson = ['value_ids' => $valueIds];

                $variant = ProductVariant::create([
                    'product_id'    => $product->id,
                    'sku'           => Arr::get($combo, 'sku'),
                    'attributes'    => $attrJson,
                    'price'         => Arr::get($combo, 'price'),
                    'compare_price' => Arr::get($combo, 'compare_price'),
                    'weight'        => Arr::get($combo, 'weight'),
                    'length'        => Arr::get($combo, 'length'),
                    'width'         => Arr::get($combo, 'width'),
                    'height'        => Arr::get($combo, 'height'),
                    'is_active'     => Arr::get($combo, 'is_active', 1),
                ]);

                // map value_ids -> pivot (gắn cho variant)
                $rows = [];
                foreach ($valueIds as $vid) {
                    $rows[] = [
                        'product_id'         => $product->id,
                        'product_variant_id' => $variant->id,
                        // lấy attribute_id từ attribute_values hoặc từ bảng product_attribute_values đã gắn trước đó:
                        // ở đây chèn tạm attribute_id = null, nhưng tốt nhất lookup:
                        'attribute_id'       => DB::table('attribute_values')->where('id', $vid)->value('attribute_id'),
                        'attribute_value_id' => $vid,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('product_attribute_values')->insert($rows);

                $count++;
            }
        });

        return $count;
    }

    public function update(ProductVariant $variant, array $data): ProductVariant
    {
        $variant->update(Arr::only($data, [
            'sku','price','compare_price','weight','length','width','height','is_active'
        ]));
        return $variant;
    }

    public function delete(ProductVariant $variant): void
    {
        DB::transaction(function() use ($variant) {
            DB::table('product_attribute_values')
              ->where('product_variant_id', $variant->id)
              ->delete();
            $variant->delete(); // nếu muốn soft delete thì thêm SoftDeletes vào model và cột DB
        });
    }
}
