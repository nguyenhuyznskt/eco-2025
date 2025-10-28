<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_shop_id','vendor_id','rate','amount','status','released_at','meta'
    ];
    protected $casts = ['meta'=>'array','released_at'=>'datetime'];

    public function shop(){ return $this->belongsTo(OrderShop::class,'order_shop_id'); }
    public function vendor(){ return $this->belongsTo(Vendor::class); }
}
