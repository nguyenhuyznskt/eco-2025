<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Services\Admin\AttributeService;
use App\Services\Admin\SoftDeleteService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(
        protected AttributeService $svc,
        protected SoftDeleteService $trash
    ) {}

    public function index(Request $request)
    {
        $data = $this->svc->paginateWithSearch($request->all());
        return view('admin.attributes.index', compact('data'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|max:191',
            'slug'          => 'nullable|max:191',
            'type'          => 'nullable|in:text,select,color,size,number',
            'is_filterable' => 'boolean',
            'is_variation'  => 'boolean',
        ]);

        $this->svc->store($validated);
        return redirect()->route('admin.attribute.index')->with('success', 'Tạo thuộc tính thành công!');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name'          => 'required|max:191',
            'slug'          => 'nullable|max:191',
            'type'          => 'nullable|in:text,select,color,size,number',
            'is_filterable' => 'boolean',
            'is_variation'  => 'boolean',
        ]);

        $this->svc->update($attribute, $validated);
        return redirect()->route('admin.attribute.index')->with('success', 'Cập nhật thành công!');
    }

    /** Xoá mềm 1 bản ghi (kèm xoá values liên quan) */
    public function destroy(Attribute $attribute)
    {
        $this->trash->deleteOne($attribute, function (Attribute $attr) {
            $attr->values()->delete(); // tuỳ bảng values có SoftDeletes hay không
        });

        return redirect()->route('admin.attribute.index')->with('success', 'Đã xoá thuộc tính!');
    }

    /** Trang thùng rác */
    public function trashed()
    {
        $data = $this->trash->trashed(Attribute::class, 10);
        return view('admin.attributes.trashed', compact('data'));
    }

    /** Khôi phục 1 item theo id */
    public function restore($id)
    {
        $ok = $this->trash->restoreById(Attribute::class, $id);
        return redirect()->route('admin.attribute.trashed')
            ->with($ok ? 'success' : 'error', $ok ? 'Khôi phục thành công!' : 'Khôi phục thất bại!');
    }

    /** Xoá vĩnh viễn 1 item theo id */
    public function forceDelete($id)
    {
        $ok = $this->trash->forceDeleteById(Attribute::class, $id);
        return redirect()->route('admin.attribute.trashed')
            ->with($ok ? 'success' : 'error', $ok ? 'Đã xoá vĩnh viễn!' : 'Xoá vĩnh viễn thất bại!');
    }

    /** Xoá mềm nhiều từ danh sách chính (kèm xoá values liên quan) */
    public function bulkDelete(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (!array_filter($ids)) {
            return back()->with('error', 'Chưa chọn thuộc tính nào để xoá.');
        }

        $count = $this->trash->bulkDelete(Attribute::class, $ids, function (Attribute $attr) {
            $attr->values()->delete();
        });

        return redirect()->route('admin.attribute.index')
            ->with($count ? 'success' : 'error', $count ? "Đã xoá {$count} thuộc tính!" : 'Xoá thất bại.');
    }

    /** Khôi phục nhiều trong thùng rác */
    public function bulkRestore(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (!array_filter($ids)) {
            return back()->with('error', 'Chưa chọn bản ghi nào để khôi phục.');
        }

        $count = $this->trash->bulkRestore(Attribute::class, $ids);
        return back()->with('success', "Đã khôi phục {$count} thuộc tính!");
    }

    /** Xoá vĩnh viễn nhiều trong thùng rác */
    public function bulkForceDelete(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (!array_filter($ids)) {
            return back()->with('error', 'Chưa chọn bản ghi nào để xoá vĩnh viễn.');
        }

        $count = $this->trash->bulkForceDelete(Attribute::class, $ids);
        return back()->with('success', "Đã xoá vĩnh viễn {$count} thuộc tính!");
    }

    /** Xoá vĩnh viễn toàn bộ trong thùng rác */
    public function forceDeleteAll()
    {
        $count = $this->trash->forceDeleteAll(Attribute::class);
        return redirect()->route('admin.attribute.trashed')
            ->with($count ? 'success' : 'error', $count ? "Đã xoá vĩnh viễn {$count} mục!" : 'Thùng rác đang trống.');
    }
}
