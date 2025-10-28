<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\DB;

class ProductAttributeService
{
    public function paginate()
    {
        return ProductAttributeValue::with(['product','attributeValue.attribute'])
            ->orderByDesc('id')->paginate(15);
    }

    public function assign(Product $product, array $valueIds)
    {
        DB::transaction(function() use ($product, $valueIds) {
            $product->attributeValues()->sync($valueIds);
        });
    }

    public function detach(Product $product, AttributeValue $value)
    {
        $product->attributeValues()->detach($value->id);
    }
}
