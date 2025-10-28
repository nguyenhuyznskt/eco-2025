<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['user_id','shop_name','slug','description','tax_code','address','phone','logo','status','settings'];
    protected $casts = ['settings' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function products() { return $this->hasMany(Product::class); }
}
