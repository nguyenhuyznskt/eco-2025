<?php

namespace App\Services\Admin;

use App\Models\AttributeValue;
use App\Models\Attribute;

class AttributeValueService
{
    public function paginate($filters = [])
    {
        $q = AttributeValue::query()->with('attribute');
        if (!empty($filters['s'])) {
            $q->where('value', 'like', '%' . trim($filters['s']) . '%');
        }
        return $q->orderByDesc('id')->paginate(15);
    }

    public function store(array $data)
    {
        return AttributeValue::create($data);
    }

    public function update(AttributeValue $value, array $data)
    {
        $value->update($data);
        return $value;
    }

    public function destroy(AttributeValue $value)
    {
        $value->delete();
    }
}
