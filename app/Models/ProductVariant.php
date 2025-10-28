<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'sku',
        'attributes',
        'price',
        'compare_price',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
    ];

    protected $casts = ['attributes' => 'array'];
    public function product(){ return $this->belongsTo(Product::class); }
    public function values() {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 
            'product_variant_id','attribute_value_id')
            ->withPivot(['attribute_id','product_id'])
            ->withTimestamps();
    }

 
}
