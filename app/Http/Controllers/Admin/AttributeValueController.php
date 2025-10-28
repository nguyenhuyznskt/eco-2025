<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\Admin\AttributeValueService;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function __construct(protected AttributeValueService $svc) {}

    public function index(Request $request)
    {
        $data = $this->svc->paginate($request->all());
        return view('admin.attribute_values.index', compact('data'));
    }

    public function create()
    {
        $attributes = Attribute::pluck('name','id');
        return view('admin.attribute_values.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:191',
        ]);

        $this->svc->store($validated);
        return redirect()->route('admin.attribute_value.index')->with('success','Thêm giá trị thành công!');
    }

    public function edit(AttributeValue $attributeValue)
    {
        $attributes = Attribute::pluck('name','id');
        return view('admin.attribute_values.edit', compact('attributeValue','attributes'));
    }

    public function update(Request $request, AttributeValue $attributeValue)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:191',
        ]);

        $this->svc->update($attributeValue, $validated);
        return redirect()->route('admin.attribute_value.index')->with('success','Cập nhật thành công!');
    }

    public function destroy(AttributeValue $attributeValue)
    {
        $this->svc->destroy($attributeValue);
        return redirect()->route('admin.attribute_value.index')->with('success','Đã xóa!');
    }
}
