<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = ['product_variant_id','qty_available','qty_reserved','qty_sold'];
    public function variant() { return $this->belongsTo(ProductVariant::class,'product_variant_id'); }

}
