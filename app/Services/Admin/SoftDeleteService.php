<?php

namespace App\Services\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SoftDeleteService
{
    /**
     * Lấy danh sách đã xoá mềm (kèm phân trang & giữ query string)
     * @param class-string<Model> $modelClass
     */
    public function trashed(string $modelClass, int $perPage = 15)
    {
        return $modelClass::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Xoá mềm 1 bản ghi (cho phép hook xử lý quan hệ trước khi xoá)
     */
    public function deleteOne(Model $model, ?callable $before = null): bool
    {
        return DB::transaction(function () use ($model, $before) {
            if ($before) $before($model);
            return (bool) $model->delete();
        });
    }

    /**
     * Xoá mềm nhiều theo danh sách id (có hook xử lý quan hệ từng item)
     * @param class-string<Model> $modelClass
     */
    public function bulkDelete(string $modelClass, array $ids, ?callable $beforeEach = null): int
    {
        $ids = array_values(array_filter(Arr::wrap($ids)));
        if (empty($ids)) return 0;

        return DB::transaction(function () use ($modelClass, $ids, $beforeEach) {
            $count = 0;
            $key   = (new $modelClass)->getKeyName();

            /** @var \Illuminate\Database\Eloquent\Collection<int, Model> $items */
            $items = $modelClass::whereIn($key, $ids)->get();

            foreach ($items as $item) {
                if ($beforeEach) $beforeEach($item);
                $item->delete();
                $count++;
            }
            return $count;
        });
    }

    /**
     * Khôi phục theo ID
     * @param class-string<Model> $modelClass
     */
    public function restoreById(string $modelClass, int|string $id): bool
    {
        return (bool) $modelClass::onlyTrashed()->whereKey($id)->restore();
    }

    /**
     * Khôi phục nhiều
     * @param class-string<Model> $modelClass
     */
    public function bulkRestore(string $modelClass, array $ids): int
    {
        $ids = array_values(array_filter(Arr::wrap($ids)));
        if (empty($ids)) return 0;

        $key = (new $modelClass)->getKeyName();
        return (int) $modelClass::onlyTrashed()->whereIn($key, $ids)->restore();
    }

    /**
     * Xoá vĩnh viễn theo ID
     * @param class-string<Model> $modelClass
     */
    public function forceDeleteById(string $modelClass, int|string $id): bool
    {
        $row = $modelClass::onlyTrashed()->whereKey($id)->first();
        return $row ? (bool) $row->forceDelete() : false;
    }

    /**
     * Xoá vĩnh viễn nhiều
     * @param class-string<Model> $modelClass
     */
    public function bulkForceDelete(string $modelClass, array $ids): int
    {
        $ids = array_values(array_filter(Arr::wrap($ids)));
        if (empty($ids)) return 0;

        $key = (new $modelClass)->getKeyName();
        return (int) $modelClass::onlyTrashed()->whereIn($key, $ids)->forceDelete();
    }

    /**
     * Xoá vĩnh viễn toàn bộ trong thùng rác
     * @param class-string<Model> $modelClass
     */
    public function forceDeleteAll(string $modelClass): int
    {
        return (int) $modelClass::onlyTrashed()->forceDelete();
    }
}
