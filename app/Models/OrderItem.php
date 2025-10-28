<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id','order_shop_id','product_id','product_variant_id',
        'name','sku','qty','price','total','meta'
    ];
    protected $casts = ['meta'=>'array'];

    public function order()   { return $this->belongsTo(Order::class); }
    public function shop()    { return $this->belongsTo(OrderShop::class,'order_shop_id'); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class,'product_variant_id'); }
}
