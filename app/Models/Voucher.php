<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'code','name','description','type','value','max_discount','min_order',
        'usage_limit','usage_per_user','starts_at','ends_at','is_active','meta'
    ];
    protected $casts = [
        'starts_at'=>'datetime','ends_at'=>'datetime','is_active'=>'boolean','meta'=>'array'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'voucher_user')
            ->withPivot(['used_at','order_id'])
            ->withTimestamps();
    }
}
