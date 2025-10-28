<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Services\Admin\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(protected AttributeService $svc) {}

    public function index(Request $request)
    {
        $data = $this->svc->paginate($request->all());
        return view('admin.attributes.index', compact('data'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:191',
            'slug' => 'nullable|max:191',
            'type' => 'nullable|in:text,select,color,size,number',
            'is_filterable' => 'boolean',
            'is_variation' => 'boolean',
        ]);

        $this->svc->store($validated);
        return redirect()->route('admin.attribute.index')->with('success','Tạo thuộc tính thành công!');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|max:191',
            'slug' => 'nullable|max:191',
            'type' => 'nullable|in:text,select,color,size,number',
            'is_filterable' => 'boolean',
            'is_variation' => 'boolean',
        ]);

        $this->svc->update($attribute, $validated);
        return redirect()->route('admin.attribute.index')->with('success','Cập nhật thành công!');
    }

    public function destroy(Attribute $attribute)
    {
        $this->svc->destroy($attribute);
        return redirect()->route('admin.attribute.index')->with('success','Đã xóa thuộc tính!');
    }
}
