<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductCrudService
{
    public function create(array $productData, array $attrMap = [], array $combinations = []): Product
    {
        return DB::transaction(function() use ($productData, $attrMap, $combinations) {
    
            // 🔹 1. Tạo slug tự động nếu trống
            if (empty($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']) . '-' . uniqid();
            }
    
            // 🔹 2. Xử lý meta nếu có (convert mảng -> JSON)
          
    
            // 🔹 3. Tạo product
            $product = Product::create($productData);
    
            // 🔹 4. Gán attribute values cấp product
            $rows = [];
            foreach ($attrMap as $attrId => $valueIds) {
                foreach ($valueIds as $valId) {
                    $rows[] = [
                        'product_id' => $product->id,
                        'product_variant_id' => null,
                        'attribute_id' => $attrId,
                        'attribute_value_id' => $valId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if ($rows) DB::table('product_attribute_values')->insert($rows);
    
            // 🔹 5. Tạo variants từ tổ hợp
            foreach ($combinations as $combo) {
                $valueIds = Arr::get($combo, 'value_ids', []);
                sort($valueIds);
    
                $rawSku = trim((string) Arr::get($combo, 'sku'));
if ($rawSku === '' || $rawSku === null) {
    $base = Str::slug($product->name, '-');
    $uniq = strtoupper(Str::random(5));
    $rawSku = $base . '-' . $uniq;
}

$variant = ProductVariant::create([
    'product_id'    => $product->id,
    'sku'           => $rawSku,
    'attributes'    => ['value_ids' => $valueIds],
    'price'         => Arr::get($combo, 'price', 0),
    'compare_price' => Arr::get($combo, 'compare_price', 0),
    'is_active'     => Arr::get($combo, 'is_active', 1),
]);

    
                // 🔹 6. Nếu biến thể có ảnh, lưu vào bảng product_images
                if (!empty($combo['image'])) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'path'       => $combo['image'],
                        'is_primary' => 1,
                    ]);
                }
    
                // 🔹 7. Gán attribute_value cho variant
                $pairs = DB::table('attribute_values')->whereIn('id', $valueIds)
                    ->get(['id as attribute_value_id', 'attribute_id']);
                $pivot = [];
                foreach ($pairs as $p) {
                    $pivot[] = [
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_id' => $p->attribute_id,
                        'attribute_value_id' => $p->attribute_value_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if ($pivot) DB::table('product_attribute_values')->insert($pivot);
            }
    
            return $product;
        });
    }
    

    public function update(
        Product $product,
        array $productData,
        array $attrMap = [],
        array $combinations = [],
        bool $replaceVariants = false
    ): Product {
        return DB::transaction(function () use ($product, $productData, $attrMap, $combinations, $replaceVariants) {
            $product->update($productData);
    
            // cập nhật attribute map cấp product
            $this->syncProductAttributeValues($product->id, $attrMap);
    
            if ($replaceVariants) {
                // xoá các variant cũ
                DB::table('product_attribute_values')
                    ->where('product_id', $product->id)
                    ->whereNotNull('product_variant_id')
                    ->delete();
    
                ProductVariant::where('product_id', $product->id)->delete();
    
                // tạo lại variants
                foreach ($combinations as $combo) {
                    $valueIds = Arr::get($combo, 'value_ids', []);
                    sort($valueIds);
    
                    $variant = ProductVariant::create([
                        'product_id'    => $product->id,
                        'sku'           => trim((string) Arr::get($combo, 'sku')) ?: 'SKU-' . uniqid(),
                        'attributes'    => ['value_ids' => $valueIds],
                        'price'         => (float) Arr::get($combo, 'price', 0),
                        'compare_price' => (float) Arr::get($combo, 'compare_price', 0),
                        'is_active'     => (int) Arr::get($combo, 'is_active', 1),
                    ]);
    
                    // gán pivot attribute_value cho từng variant
                    $pairs = DB::table('attribute_values')
                        ->whereIn('id', $valueIds)
                        ->get(['id as attribute_value_id', 'attribute_id']);
    
                    $pivotRows = [];
                    foreach ($pairs as $p) {
                        $pivotRows[] = [
                            'product_id'         => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_id'       => $p->attribute_id,
                            'attribute_value_id' => $p->attribute_value_id,
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ];
                    }
                    if ($pivotRows) {
                        DB::table('product_attribute_values')->insert($pivotRows);
                    }
                }
            }
    
            return $product;
        });
    }
    

    /** attrMap: [attribute_id => [value_id,...]] */
    public function syncProductAttributeValues(int $productId, array $attrMap): void
    {
        DB::table('product_attribute_values')
            ->where('product_id', $productId)
            ->whereNull('product_variant_id')
            ->delete();

        $rows = [];
        foreach ($attrMap as $attributeId => $valueIds) {
            foreach (Arr::wrap($valueIds) as $valueId) {
                if (!$valueId) continue;
                $rows[] = [
                    'product_id'         => $productId,
                    'product_variant_id' => null,
                    'attribute_id'       => (int)$attributeId,
                    'attribute_value_id' => (int)$valueId,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
        }
        if ($rows) DB::table('product_attribute_values')->insert($rows);
    }

    /**
     * combinations[]: [
     *   value_ids: [id, id, ...],
     *   sku, price, compare_price, is_active, weight, length, width, height
     * ]
     */
    public function createVariants(int $productId, array $combinations): int
    {
        $created = 0;

        foreach ($combinations as $combo) {
            $valueIds = array_values(array_filter(array_map('intval', Arr::get($combo, 'value_ids', []))));
            if (empty($valueIds)) continue;
            sort($valueIds);

            $variant = ProductVariant::create([
                'product_id'    => $productId,
                'sku'           => Arr::get($combo, 'sku'),
                'attributes'    => ['value_ids' => $valueIds],
                'price'         => Arr::get($combo, 'price'),
                'compare_price' => Arr::get($combo, 'compare_price'),
                'weight'        => Arr::get($combo, 'weight'),
                'length'        => Arr::get($combo, 'length'),
                'width'         => Arr::get($combo, 'width'),
                'height'        => Arr::get($combo, 'height'),
                'is_active'     => Arr::get($combo, 'is_active', 1),
            ]);

            $pairs = DB::table('attribute_values')->whereIn('id', $valueIds)->get(['id as attribute_value_id', 'attribute_id']);
            $rows  = [];
            foreach ($pairs as $p) {
                $rows[] = [
                    'product_id'         => $productId,
                    'product_variant_id' => $variant->id,
                    'attribute_id'       => (int)$p->attribute_id,
                    'attribute_value_id' => (int)$p->attribute_value_id,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
            if ($rows) DB::table('product_attribute_values')->insert($rows);
            $created++;
        }

        return $created;
    }
}
