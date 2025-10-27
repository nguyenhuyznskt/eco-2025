<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService implements ProductServiceInterface
{
    public function all(array $filters = []):\Illuminate\Database\Eloquent\Collection
    {
        return Product::with('categories')->get();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::with('categories');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::with('categories')->find($id);
    }

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }

        $product = Product::create($data);

        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return $product;
    }

    public function update(int $id, array $data, ?UploadedFile $image = null): Product
    {
        $product = Product::findOrFail($id);

        if ($image) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $image->store('products', 'public');
        }

        $product->update($data);

        if (isset($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return $product;
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->categories()->detach();

        return $product->delete();
    }
}
