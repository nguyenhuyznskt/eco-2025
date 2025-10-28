<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShop extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id','vendor_id','subtotal','shipping_fee','tax_amount',
        'discount_amount','total_amount','status','meta'
    ];
    protected $casts = ['meta'=>'array'];

    public function order()  { return $this->belongsTo(Order::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function items()  { return $this->hasMany(OrderItem::class,'order_shop_id'); }
    public function shipments(){ return $this->hasMany(Shipment::class); }
}
