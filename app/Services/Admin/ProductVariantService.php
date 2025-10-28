<?php

namespace App\Services\Admin;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductVariantService
{
    //
    public function getByProductId($productId): Collection
    {
        return ProductVariant::where('product_id', $productId)->get();
    }

    public function create(array $data): ProductVariant
    {
        return ProductVariant::create($data);
    }

    public function update(ProductVariant $variant, array $data): ProductVariant
    {
        $variant->update($data);
        return $variant;
    }

    public function delete(ProductVariant $variant): bool
    {
        return $variant->delete();
    }
    public function createProductWithVariants(array $data)
    {
        // 1. Tạo sản phẩm cha
        $product = Product::create($data);

        // 2. Lấy các attributes có is_variation = 1
        $variationAttributes = Attribute::with('values')
            ->where('is_variation', true)
            ->get();

        // Nếu không có attribute nào là biến thể => return luôn
        if ($variationAttributes->isEmpty()) return $product;

        // 3. Sinh combinations từ attribute values
        $combos = $this->generateCombinations(
            $variationAttributes->pluck('values.*.id', 'name')->toArray()
        );

        // 4. Tạo từng variant
        foreach ($combos as $combo) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $product->name . ' - ' . implode(' / ', $combo),
                'attributes' => json_encode($combo),
                'price' => $data['price'] ?? 0,
                'stock' => 0,
                'sku' => strtoupper(Str::random(8)),
            ]);
        }

        return $product;
    }

    // Sinh tổ hợp từ mảng các attribute values
    private function generateCombinations(array $arrays)
    {
        $result = [[]];
        foreach ($arrays as $attribute => $values) {
            $new = [];
            foreach ($result as $combo) {
                foreach ($values as $value) {
                    $new[] = array_merge($combo, [$attribute => $value]);
                }
            }
            $result = $new;
        }
        return $result;
    }
}
