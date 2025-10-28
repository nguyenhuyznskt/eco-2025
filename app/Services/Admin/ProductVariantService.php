<?php

namespace App\Services\Admin;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

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
}
