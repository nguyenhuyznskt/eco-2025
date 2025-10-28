<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\Admin\AttributeValueService;
use App\Services\Admin\SoftDeleteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AttributeValueController extends Controller
{
    public function __construct(
        protected AttributeValueService $svc,
        protected SoftDeleteService $trash
    ) {}

    // LIST
    public function index(Request $request): View
    {
        $data = $this->svc->paginateWithSearch($request->all());
        $attributes = Attribute::pluck('name','id');
        return view('admin.attribute_values.index', compact('data','attributes'));
    }

    // CREATE
    public function create(): View
    {
        $attributes = Attribute::pluck('name','id');
        return view('admin.attribute_values.create', compact('attributes'));
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'attribute_id'=>'required|exists:attributes,id',
            'value'=>'required|string|max:255',
            'label'=>'nullable|string|max:255',
            'sort_order'=>'nullable|integer|min:0',
        ]);
        $this->svc->store($validated);
        return redirect()->route('admin.attribute_value.index')->with('success','Đã thêm');
    }

    // EDIT
    public function edit(AttributeValue $attributeValue)
    {
        // Lấy danh sách attribute cha để chọn trong form
        $attributes = Attribute::pluck('name', 'id');
    
        // Trả dữ liệu cho view, truyền biến $row
        return view('admin.attribute_values.edit', [
            'row' => $attributeValue,
            'attributes' => $attributes,
        ]);
    }
    
    public function update(Request $request, AttributeValue $attributeValue): RedirectResponse
    {
        $validated = $request->validate([
            'attribute_id'=>'required|exists:attributes,id',
            'value'=>'required|string|max:255',
            'label'=>'nullable|string|max:255',
            'sort_order'=>'nullable|integer|min:0',
        ]);
        $this->svc->update($attributeValue,$validated);
        return redirect()->route('admin.attribute_value.index')->with('success','Đã cập nhật');
    }

    // SOFT DELETE 1
    public function destroy(AttributeValue $attributeValue): RedirectResponse
    {
        $this->trash->deleteOne($attributeValue);
        return back()->with('success','Đã xóa mềm');
    }

    // BULK SOFT DELETE
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->trash->bulkDelete(AttributeValue::class, $ids);
        return back()->with('success',"Đã xóa mềm $count mục");
    }

    // TRASH LIST
    public function trashed(): View
    {
        $data = $this->trash->trashed(AttributeValue::class);
        return view('admin.attribute_values.trashed', compact('data'));
    }

    // RESTORE 1 + BULK
    public function restore($id): RedirectResponse
    {
        $this->trash->restoreById(AttributeValue::class, $id);
        return back()->with('success','Đã khôi phục');
    }
   public function bulkRestore(Request $request): RedirectResponse
{
    $ids = Arr::wrap($request->input('ids', []));
    if (empty($ids)) {
        return back()->with('error','Không có mục nào được chọn.');
    }
    $restored = AttributeValue::onlyTrashed()->whereIn('id', $ids)->restore();
    return back()->with('success', "Đã khôi phục {$restored} mục đã chọn!");
}


    // FORCE DELETE 1 + BULK
    public function forceDelete($id): RedirectResponse
    {
        $this->trash->forceDeleteById(AttributeValue::class, $id);
        return back()->with('success','Đã xóa vĩnh viễn');
    }
    public function bulkForceDelete(Request $request): RedirectResponse
    {
        $ids = Arr::wrap($request->input('ids', []));
    if (empty($ids)) {
        return back()->with('error','Không có mục nào được chọn.');
    }
    $deleted = $this->trash->bulkForceDelete(AttributeValue::class, $ids);
    return back()->with('success', "Đã xóa vĩnh viễn {$deleted} giá trị đã chọn!");
    }
    
    
}
