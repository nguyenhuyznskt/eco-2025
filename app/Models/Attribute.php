<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','slug','type','is_filterable','is_variation',];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug)) $m->slug = Str::slug($m->name);
        });
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }
}
