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
        'code', 'type', 'value', 'max_value', 'min_order_amount',
        'start_at', 'end_at', 'usage_limit_global', 'usage_limit_per_user',
        'used_count', 'is_active', 'meta',
    ];
    protected $casts = [
        'meta' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'voucher_user')
            ->withPivot(['used_at','order_id'])
            ->withTimestamps();
    }
}
