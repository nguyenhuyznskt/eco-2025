<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\ProductAttributeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AdminController::class, 'dashboard'])->name('index');
Route::get('admin/', [AdminController::class, 'dashboard'])->name('index');
Route::get('client/', [AdminController::class, 'dashboard'])->name('index');



Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
// ->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::prefix('admin')->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('indexCategory'); // list
        Route::get('/{id}/show', [CategoryController::class, 'show'])->name('categoryShow');
        Route::get('/create', [CategoryController::class, 'create'])->name('createCategory');
        Route::post('/store', [CategoryController::class, 'store'])->name('categoryStore'); // lưu
        Route::get('/{id}/createChild', [CategoryController::class, 'createChild'])->name('createChildCategory'); // show form
        Route::post('/storeChild', [CategoryController::class, 'storeChild'])->name('categoryStoreChild'); // lưu

        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categoryEdit'); // form edit
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categoryUpdate'); // update
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categoryDestroy'); // xóa
        Route::get('category/trashed', [CategoryController::class, 'trashed'])->name('categoryTrashed');
        Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('categoryRestore');
        Route::delete('category/{id}/force', [CategoryController::class, 'forceDelete'])->name('categoryForceDelete');
    });
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('indexProduct'); // list
        Route::get('/create', [ProductController::class, 'create'])->name('createProduct');  // form thêm
        Route::post('/store', [ProductController::class, 'store'])->name('storeProduct');    // lưu sản phẩm
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('editProduct');   // form sửa
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('updateProduct'); // cập nhật
        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('destroyProduct'); // xóa


    });
    Route::prefix('product/{product}/variants')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('variantIndex');
        Route::post('/', [ProductVariantController::class, 'store'])->name('variantStore');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('variantUpdate');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('variantDestroy');
    });


    Route::prefix('attribute')->name('admin.attribute.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::get('/create', [AttributeController::class, 'create'])->name('create');
        Route::post('/store', [AttributeController::class, 'store'])->name('store');

        // static trước
        Route::get('/trashed', [AttributeController::class, 'trashed'])->name('trashed');
        Route::post('/bulk-restore', [AttributeController::class, 'bulkRestore'])->name('bulkRestore');
        Route::delete('/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('bulkDelete');
        Route::delete('/bulk-force-delete', [AttributeValueController::class, 'bulkForceDelete'])->name('bulk-force-delete');


        Route::delete('/force-delete-all', [AttributeController::class, 'forceDeleteAll'])->name('forceDeleteAll');

        Route::post('/{id}/restore', [AttributeController::class, 'restore'])->name('restore')->whereNumber('id');
        Route::delete('/{id}/force', [AttributeController::class, 'forceDelete'])->name('forceDelete')->whereNumber('id');

        // dynamic sau
        Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit')->whereNumber('attribute');
        Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update')->whereNumber('attribute');
        Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy')->whereNumber('attribute');
    });

    Route::prefix('attribute-value')->name('admin.attribute_value.')->group(function () {
        // CRUD cơ bản
        Route::get('/', [AttributeValueController::class, 'index'])->name('index');
        Route::get('/create', [AttributeValueController::class, 'create'])->name('create');
        Route::post('/store', [AttributeValueController::class, 'store'])->name('store');
    
        // 🔒 Bulk + trash: đặt TRƯỚC route động
        Route::post('/bulk-delete', [AttributeValueController::class, 'bulkDelete'])->name('bulk-delete');
        Route::match(['POST','DELETE'], '/bulk-force-delete', [AttributeValueController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::post('/bulk-restore', [AttributeValueController::class, 'bulkRestore'])->name('bulk-restore');
        Route::get('/trashed', [AttributeValueController::class, 'trashed'])->name('trashed');
    
        // Khôi phục/Xóa vĩnh viễn 1 item
        Route::post('/{id}/restore', [AttributeValueController::class, 'restore'])->name('restore')->whereNumber('id');
        Route::delete('/{id}/force', [AttributeValueController::class, 'forceDelete'])->name('force-delete')->whereNumber('id');
    
        // 🔽 Route động – nhớ whereNumber để khỏi bắt nhầm
        Route::get('/{attributeValue}/edit', [AttributeValueController::class, 'edit'])->name('edit')->whereNumber('attributeValue');
        Route::put('/{attributeValue}', [AttributeValueController::class, 'update'])->name('update')->whereNumber('attributeValue');
        Route::delete('/{attributeValue}', [AttributeValueController::class, 'destroy'])->name('destroy')->whereNumber('attributeValue');
    });
    
    // Gán attribute values vào Product
    Route::prefix('product-attributes')->name('admin.product_attribute.')->group(function () {
        Route::get('/', [ProductAttributeController::class, 'index'])->name('index');
        Route::get('/assign', [ProductAttributeController::class, 'create'])->name('create');
        Route::post('/assign', [ProductAttributeController::class, 'store'])->name('store');
        Route::delete('/{product}/{attributeValue}', [ProductAttributeController::class, 'destroy'])->name('destroy');
    });
});



require __DIR__ . '/auth.php';
