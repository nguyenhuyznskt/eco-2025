<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_shop_id','carrier','service','tracking_number','status',
        'shipped_at','delivered_at','from_address','to_address','meta'
    ];
    protected $casts = [
        'from_address'=>'array','to_address'=>'array','meta'=>'array',
        'shipped_at'=>'datetime','delivered_at'=>'datetime'
    ];

    public function shop(){ return $this->belongsTo(OrderShop::class,'order_shop_id'); }
}
