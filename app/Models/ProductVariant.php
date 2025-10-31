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
    public function image()
{
    return $this->hasOne(ProductImage::class, 'variant_id');
}

    

    protected $casts = ['attributes' => 'array'];
    public function product(){ return $this->belongsTo(Product::class); }
    public function values() {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 
            'product_variant_id','attribute_value_id')
            ->withPivot(['attribute_id','product_id'])
            ->withTimestamps();
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id');
    }
    
    public function attributeValues()
{
    return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
        ->with('attribute')
        ->withPivot(['product_id'])
        ->wherePivotNotNull('product_variant_id');
}
public function getAttributeLabelComboAttribute()
{
    if (!$this->relationLoaded('attributeValues')) {
        $this->load('attributeValues.attribute');
    }

    return $this->attributeValues->map(function ($val) {
        $attrName = $val->attribute->name ?? '';
        $valLabel = $val->label ?? $val->value;
        return "{$attrName}: {$valLabel}";
    })->join(' | ');
}



 
}
