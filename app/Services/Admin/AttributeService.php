<?php

namespace App\Services\Admin;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

class AttributeService
{
    public function paginate($filters = [])
    {
        $q = Attribute::query()->withCount('values');
        if (!empty($filters['s'])) {
            $s = trim($filters['s']);
            $q->where('name','like',"%$s%")->orWhere('slug','like',"%$s%");
        }
        return $q->orderByDesc('id')->paginate(15);
    }

    public function store(array $data)
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        return Attribute::create($data);
    }

    public function update(Attribute $attr, array $data)
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $attr->update($data);
        return $attr;
    }

    public function destroy(Attribute $attr)
    {
        $attr->values()->delete();
        $attr->delete();
    }
}
