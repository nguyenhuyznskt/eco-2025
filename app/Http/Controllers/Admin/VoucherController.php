<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Services\Admin\VoucherService;
use App\Services\Admin\SoftDeleteService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct(
        protected VoucherService $svc,
        protected SoftDeleteService $trash
    ) {}

    public function index(Request $request)
    {
        $data = $this->svc->paginateWithSearch($request->all());
        return view('admin.vouchers.index', compact('data'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'usage_limit_global' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta' => 'nullable|array',
        ]);

        $this->svc->store($validated);
        return redirect()->route('admin.voucher.index')->with('success', 'Thêm voucher thành công');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'usage_limit_global' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta' => 'nullable|array',
        ]);

        $this->svc->update($voucher, $validated);
        return redirect()->route('admin.voucher.index')->with('success', 'Cập nhật voucher thành công');
    }

    public function destroy(Voucher $voucher)
    {
        $this->svc->destroy($voucher);
        return back()->with('success', 'Đã xóa voucher');
    }

    public function bulkDelete(Request $request)
    {
        $this->svc->bulkDelete($request->ids ?? []);
        return back()->with('success', 'Đã xóa các voucher đã chọn');
    }

    public function trashed()
    {
        $data = Voucher::onlyTrashed()->paginate(10);
        return view('admin.vouchers.trashed', compact('data'));
    }

    public function bulkRestore(Request $request)
    {
        $this->svc->bulkRestore($request->ids ?? []);
        return back()->with('success', 'Khôi phục thành công');
    }

    public function bulkForceDelete(Request $request)
    {
        $this->svc->bulkForceDelete($request->ids ?? []);
        return back()->with('success', 'Đã xóa vĩnh viễn');
    }
}
