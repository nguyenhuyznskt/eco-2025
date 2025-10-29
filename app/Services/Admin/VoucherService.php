<?php

namespace App\Services\Admin;

use App\Models\Voucher;
use Illuminate\Support\Carbon;

class VoucherService
{
    public function paginateWithSearch(array $filters = [])
    {
        $query = Voucher::query();
    
        if (!empty($filters['keyword'])) {
            $query->where('code', 'like', '%' . $filters['keyword'] . '%');
        }
    
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
    
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active' ? 1 : 0);
        }
    
        if (!empty($filters['validity'])) {
            $now = now();
            switch ($filters['validity']) {
                case 'active':
                    $query->where(function ($q) use ($now) {
                        $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                    })->where(function ($q) use ($now) {
                        $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                    });
                    break;
                case 'expired':
                    $query->whereNotNull('end_at')->where('end_at', '<', $now);
                    break;
                case 'upcoming':
                    $query->whereNotNull('start_at')->where('start_at', '>', $now);
                    break;
            }
        }
    
        return $query->orderByDesc('id')->paginate(10);
    }

    public function store(array $data)
    {
        return Voucher::create($data);
    }

    public function update(Voucher $voucher, array $data)
    {
        $voucher->update($data);
        return $voucher;
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
    }

    public function bulkDelete(array $ids)
    {
        Voucher::whereIn('id', $ids)->delete();
    }

    public function bulkRestore(array $ids)
    {
        Voucher::onlyTrashed()->whereIn('id', $ids)->restore();
    }

    public function bulkForceDelete(array $ids)
    {
        Voucher::onlyTrashed()->whereIn('id', $ids)->forceDelete();
    }
}
