<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $categories;
    public function __construct(CategoryService $categoryService){
        $this->categories = $categoryService;
    }
    public function index()
    {
        //
        $categories = $this->categories->getAllCategory();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categories->getRootCategories();
        return view('admin.category.CreateCategory',compact('categories'));
    }
    public function createChild()
    {
       
       $catparent = $this->categories->getAllCategory();
        return view('admin.category.createChild', compact('catparent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
    
        $this->categories->createCategory($data);
    
        return redirect()->back()->with('success', 'Tạo danh mục thành công!');
        // dd($request);
    }   
    public function storeChild(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'sort_order' => 'nullable|integer',
        //     'is_active' => 'boolean',
        //     'parent_id' => 'nullable|exists:categories,id'
        // ]);
    
        // $this->categories->createCategory($data);
    
        // return redirect()->back()->with('success', 'Tạo danh mục thành công!');
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

   
    public function destroy(string $id)
    {
        //
    }
    public function trashed()
    {
        //
    }
    public function restore(string $id)
    {
        //
    }
    public function forceDelete(string $id)
    {
        //
    }

     /**
     * Remove the specified resource from storage.
     */
}
