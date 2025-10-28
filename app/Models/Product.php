<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'compare_price',
        'is_active',
        'is_featured',
        'views',
        'meta',
    ];
    
    public function variants()
{
    return $this->hasMany(ProductVariant::class);
}

public function images()
{
    return $this->hasMany(ProductImage::class);
}

public function attributes()
{
    return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
        ->withPivot(['attribute_id','product_variant_id'])
        ->withTimestamps();
}
public function categories()
{
    return $this->belongsToMany(\App\Models\Categories::class, 'category_product', 'product_id', 'category_id');
}




}
