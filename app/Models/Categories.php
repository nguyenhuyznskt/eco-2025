<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Categories extends Model
{
    use NodeTrait;
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active', 'parent_id'];
}
