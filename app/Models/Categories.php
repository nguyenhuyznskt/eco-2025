<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use NodeTrait;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active', 'parent_id'];
    public function parent()
{
    return $this->belongsTo(Categories::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Categories::class, 'parent_id');
}
public function products()
{
    return $this->belongsToMany(\App\Models\Product::class, 'category_product', 'category_id', 'product_id');
}


}
