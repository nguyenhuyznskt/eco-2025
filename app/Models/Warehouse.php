<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name','code','address','is_active','meta'];
    protected $casts = ['address'=>'array','meta'=>'array','is_active'=>'boolean'];
}
