<?php

namespace App\Services\Admin;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Str;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    //
    protected $categories;
    public function __construct(Categories $categories){
        $this->categories = $categories;
    }
    public function getAllCategory(){
        $categories = Categories::all();
        return $categories;
    }
    public function createCategory(array $data)
    {
        $slug = Str::slug($data['name']);

        $category = new Categories([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (!empty($data['parent_id'])) {
            // Gắn vào parent
            $parent = Categories::findOrFail($data['parent_id']);
            $parent->appendNode($category);
        } else {
            // Tạo category cha
            $category->saveAsRoot();
        }

        return $category;
    }
    public function getAllWithChildren()
{
    return Categories::with('children')
        ->whereNull('parent_id')
        ->get();
}


    public function create(array $data)
    {
        DB::beginTransaction();
    
        try {
            if (!empty($data['parent_id'])) {
                // Tạo danh mục con
                $parent = Categories::findOrFail($data['parent_id']);
                $parent->children()->create([
                    'name' => $data['name'],
                    'slug' => $data['slug'] ?? Str::slug($data['name']),
                    'description' => $data['description'] ?? null,
                    'sort_order' => $data['sort_order'] ?? 0,
                    'is_active' => $data['is_active'] ?? 1,
                ]);
            } else {
                // Tạo danh mục cha
                Categories::create([
                    'name' => $data['name'],
                    'slug' => $data['slug'] ?? Str::slug($data['name']),
                    'description' => $data['description'] ?? null,
                    'sort_order' => $data['sort_order'] ?? 0,
                    'is_active' => $data['is_active'] ?? 1,
                ]);
            }
    
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete(); // soft delete
        return redirect()->route('indexCategory')->with('success', 'Xóa thành công (soft delete)');
    }
    public function trashed()
    {
        $categories = Categories::onlyTrashed()->get();
        return view('', compact('categories'));
    }
    public function restore($id)
    {
        $category = Categories::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categoryTrashed')->with('success', 'Khôi phục thành công');
    }
    public function forceDelete($id)
    {
        $category = Categories::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->route('categoryTrashed')->with('success', 'Xóa vĩnh viễn thành công');
    }
    public function getRootCategories()
{
    return Categories::whereIsRoot()->get();
}
public function getCategoryById($id)
    {
        return Categories::findOrFail($id);
    }

    // Lấy cha của category
    public function getParentById($id)
    {
        $category = Categories::findOrFail($id);
        return $category->parent; // nestedset đã có quan hệ parent()
    }



}
