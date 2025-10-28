@extends('admin.layouts.layout')

@section('content')

{{-- 🔔 Toast --}}
@if (session('success') || session('error'))
  <div id="flash-toast"
       class="fixed top-6 right-6 z-50 flex items-center gap-2 px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white
              {{ session('success') ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-600' }}">
    <i class="bx {{ session('success') ? 'bx-check-circle' : 'bx-x-circle' }} text-lg"></i>
    <span>{{ session('success') ?? session('error') }}</span>
  </div>
@endif

<div class="p-6 space-y-6">

  {{-- 🧩 Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">➕ Thêm sản phẩm mới</h1>
      <p class="text-gray-500 mt-1 text-sm">Nhập thông tin chi tiết để tạo sản phẩm mới trong cửa hàng.</p>
    </div>

    <a href="{{ route('indexProduct') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all duration-200">
      <i class="bx bx-arrow-back text-lg"></i>
      <span>Quay lại danh sách</span>
    </a>
  </div>

  {{-- 🧾 Form thêm sản phẩm --}}
  <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded-xl border border-gray-200 p-6 space-y-6 max-w-5xl mx-auto">
    @csrf

    {{-- 🔹 Tên & slug --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
        @error('name')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
      </div>
    </div>

    {{-- 🔹 Danh mục --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Danh mục</label>
      <select name="category_ids[]" multiple
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-32 text-sm">
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      <p class="text-gray-500 text-sm mt-1">Giữ Ctrl (hoặc Cmd) để chọn nhiều danh mục</p>
    </div>

    {{-- 🔹 Giá bán & Giá gốc --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">Giá bán <span class="text-red-500">*</span></label>
        <input type="number" name="price" value="{{ old('price') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
        <input type="number" name="compare_price" value="{{ old('compare_price') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
      </div>
    </div>

    {{-- 🔹 Mô tả --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
      <textarea name="short_description" rows="2"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ old('short_description') }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
      <textarea name="description" rows="5"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ old('description') }}</textarea>
    </div>

    {{-- 🔹 Ảnh --}}
    <div class="md:w-1/2">
      <label class="block text-sm font-medium text-gray-700">Ảnh sản phẩm</label>
      <input type="file" name="image" accept="image/*"
             class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:ring-indigo-500 focus:border-indigo-500">
      <p class="text-gray-500 text-sm mt-1">Chỉ chọn 1 ảnh đại diện (jpg, png...)</p>
    </div>

    {{-- 🔹 Checkbox --}}
    <div class="flex flex-wrap gap-6 mt-4">
      <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" checked
               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        <span class="text-sm text-gray-700">Hiển thị</span>
      </label>

      <label class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1"
               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        <span class="text-sm text-gray-700">Sản phẩm nổi bật</span>
      </label>
    </div>

    {{-- 🔹 Buttons --}}
    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
      <a href="{{ route('indexProduct') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm">
        <i class="bx bx-arrow-back text-base"></i> Quay lại
      </a>

      <button type="submit"
              class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-md shadow hover:shadow-md hover:scale-[1.02] transition text-sm">
        <i class="bx bx-save text-base"></i> Lưu sản phẩm
      </button>
    </div>
  </form>
</div>

{{-- 🧠 Script toast --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toast = document.getElementById('flash-toast');
  if (toast) {
    setTimeout(() => {
      toast.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => toast.remove(), 600);
    }, 4000);
  }
});
</script>

@endsection
