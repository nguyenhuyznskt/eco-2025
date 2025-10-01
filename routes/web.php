<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
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
Route::get('/',[AdminController::class, 'dashboard'])->name('index');
Route::get('admin/',[AdminController::class, 'dashboard'])->name('index');
Route::get('client/',[AdminController::class, 'dashboard'])->name('index');



Route::get('/admin/dashboard',[AdminController::class, 'dashboard'])->name('dashboard');
// ->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::prefix('admin')->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('indexCategory'); // list
        Route::get('/{id}/show',[CategoryController::class,'show'])->name('categoryShow');
        Route::get('/create', [CategoryController::class, 'create'])->name('createCategory');
        Route::post('/store', [CategoryController::class, 'store'])->name('categoryStore'); // lưu
        Route::get('/createChild', [CategoryController::class, 'createChild'])->name('createChildCategory'); // show form
        Route::post('/storeChild', [CategoryController::class, 'storeChild'])->name('categoryStoreChild'); // lưu
     
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categoryEdit'); // form edit
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categoryUpdate'); // update
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categoryDestroy'); // xóa
        Route::get('category/trashed', [CategoryController::class, 'trashed'])->name('categoryTrashed');
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('categoryRestore');
    Route::delete('category/{id}/force', [CategoryController::class, 'forceDelete'])->name('categoryForceDelete');
    });
});



require __DIR__.'/auth.php';
