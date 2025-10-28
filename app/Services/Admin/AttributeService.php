<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Attribute;
use Illuminate\Support\Str;

class AttributeService
{
    public function paginate($filters = [])
    {
        $q = Attribute::query()->withCount('values');
        if (!empty($filters['s'])) {
            $s = trim($filters['s']);
            $q->where('name', 'like', "%$s%")
              ->orWhere('slug', 'like', "%$s%");
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
 

    public function paginateWithSearch(array $filters = [])
    {
        $query = Attribute::query();
    
        // Tìm kiếm theo tên hoặc slug
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
    
        // Lọc theo type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
    
        // Lọc theo filterable
        if (isset($filters['filterable']) && $filters['filterable'] !== '') {
            $query->where('is_filterable', $filters['filterable']);
        }
    
        // Lọc theo variation
        if (isset($filters['variation']) && $filters['variation'] !== '') {
            $query->where('is_variation', $filters['variation']);
        }
    
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $query->latest()->paginate(10);
    
        return method_exists($paginator, 'withQueryString')
            ? $paginator->withQueryString()
            : $paginator->appends($filters);
    }

    /**
     * Xoá mềm nhiều thuộc tính (kèm xoá values liên quan)
     * @return int số bản ghi attribute đã xoá
     */

    
    
}
