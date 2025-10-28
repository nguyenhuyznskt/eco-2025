<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number','user_id','subtotal','shipping_fee','tax_amount',
        'discount_amount','total_amount','currency','status',
        'billing_address','shipping_address','meta','payment_method','paid_at'
    ];
    protected $casts = [
        'billing_address'=>'array','shipping_address'=>'array','meta'=>'array','paid_at'=>'datetime'
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function items()  { return $this->hasMany(OrderItem::class); }
    public function shops()  { return $this->hasMany(OrderShop::class); } // nếu multi-vendor
    public function transactions(){ return $this->hasMany(Transaction::class); }
}
