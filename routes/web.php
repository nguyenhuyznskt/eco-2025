<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\VoucherController;

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
        Route::match(['POST', 'DELETE'], '/bulk-force-delete', [AttributeValueController::class, 'bulkForceDelete'])->name('bulk-force-delete');
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

    // routes/web.php (bên trong Route::prefix('admin')->group(...))
    Route::prefix('product')->name('admin.product.')->group(function () {

        // Trang danh sách chính
        Route::get('/', [ProductController::class, 'index'])->name('index');
    
        // Thùng rác (đưa lên trên trước /{product})
        Route::get('/trashed', [ProductController::class, 'trashed'])->name('trashed');
        Route::post('/bulk-restore', [ProductController::class, 'bulkRestore'])->name('bulk-restore');
        Route::match(['POST', 'DELETE'], '/bulk-force-delete', [ProductController::class, 'bulkForceDelete'])->name('bulk-force-delete');
        Route::delete('/force-delete-all', [ProductController::class, 'forceDeleteAll'])->name('force-delete-all');
        Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore')->whereNumber('id');
        Route::delete('/{id}/force', [ProductController::class, 'forceDelete'])->name('force-delete')->whereNumber('id');
    
        // CRUD chính
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit')->whereNumber('product');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update')->whereNumber('product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy')->whereNumber('product');
    
        // Bulk delete từ index
        Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
    
        // Show phải đặt cuối cùng
        Route::get('/{product}', [ProductController::class, 'show'])->name('show')->whereNumber('product');
    });
    
    
    // PRODUCT VARIANTS (dùng trong trang create/edit product)
    Route::prefix('product/{product}/variants')->name('admin.product.variant.')->whereNumber('product')->group(function () {
        Route::post('/',               [ProductVariantController::class, 'store'])->name('store');      // tạo 1 hoặc nhiều
        Route::put('/{variant}',       [ProductVariantController::class, 'update'])->name('update')->whereNumber('variant');
        Route::delete('/{variant}',    [ProductVariantController::class, 'destroy'])->name('destroy')->whereNumber('variant');
    });
    Route::pattern('voucher', '[0-9]+');

    Route::prefix('voucher')->name('admin.voucher.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
       

        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/store', [VoucherController::class, 'store'])->name('store');
    
        // bulk routes — đặt TRƯỚC route động
        Route::post('/bulk-delete', [VoucherController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/trashed', [VoucherController::class, 'trashed'])->name('trashed');
        Route::post('/bulk-restore', [VoucherController::class, 'bulkRestore'])->name('bulk-restore');
        Route::delete('/bulk-force-delete', [VoucherController::class, 'bulkForceDelete'])->name('bulk-force-delete');
    
        // route động 
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
    });
    









});



require __DIR__ . '/auth.php';
