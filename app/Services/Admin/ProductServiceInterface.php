<?php

namespace App\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

interface ProductServiceInterface
{
    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection;
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function create(array $data, ?UploadedFile $image = null): Product;
    public function update(int $id, array $data, ?UploadedFile $image = null): Product;
    public function delete(int $id): bool;
}
