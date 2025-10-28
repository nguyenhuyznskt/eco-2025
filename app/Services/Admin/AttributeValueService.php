<?php

namespace App\Services\Admin;

use App\Models\AttributeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttributeValueService
{
    public function paginateWithSearch(array $filters=[]): LengthAwarePaginator
    {
        $q = AttributeValue::with('attribute');

        if (!empty($filters['search'])) {
            $q->where('value','like','%'.$filters['search'].'%');
        }
        if (!empty($filters['attribute_id'])) {
            $q->where('attribute_id',$filters['attribute_id']);
        }
        if (!empty($filters['with_trashed'])) {
            $q->withTrashed();
        }

        return $q->orderBy('sort_order')->paginate(15)->withQueryString();
    }

    public function store(array $data): AttributeValue
    {
        $data['sort_order'] = $data['sort_order'] ?? 0;
        return AttributeValue::create($data);
    }
    

    public function update(AttributeValue $row, array $data): AttributeValue
    {
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $row->update($data);
        return $row;
    }
    
}
