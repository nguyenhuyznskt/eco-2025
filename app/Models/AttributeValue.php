<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['attribute_id','value','label','sort_order'];
    protected $dates = ['deleted_at'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
