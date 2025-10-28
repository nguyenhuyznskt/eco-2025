@extends('admin.layouts.layout')

@section('content')
<div class="p-6">
  <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">➕ Thêm sản phẩm mới</h1>

    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      {{-- Tên và slug --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
          @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Slug</label>
          <input type="text" name="slug" value="{{ old('slug') }}"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
      </div>

      {{-- Danh mục --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Danh mục</label>
        <select name="category_ids[]" multiple
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-32">
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
          @endforeach
        </select>
        <p class="text-gray-500 text-sm mt-1">Giữ Ctrl (hoặc Cmd) để chọn nhiều danh mục</p>
      </div>

      {{-- Giá --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Giá bán <span class="text-red-500">*</span></label>
          <input type="number" name="price" value="{{ old('price') }}"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
          <input type="number" name="compare_price" value="{{ old('compare_price') }}"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
      </div>

      {{-- Mô tả --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
        <textarea name="short_description" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description') }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
        <textarea name="description" rows="5"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
      </div>

      {{-- Ảnh --}}
      <div class="md:w-1/2">
        <label class="block text-sm font-medium text-gray-700">Ảnh sản phẩm</label>
        <input type="file" name="image" accept="image/*"
               class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      {{-- Checkbox --}}
      <div class="flex gap-6 mt-4">
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="is_active" value="1" checked
                 class="rounded text-indigo-600 focus:ring-indigo-500">
          <span class="text-sm text-gray-700">Hiển thị</span>
        </label>

        <label class="flex items-center space-x-2">
          <input type="checkbox" name="is_featured" value="1"
                 class="rounded text-indigo-600 focus:ring-indigo-500">
          <span class="text-sm text-gray-700">Sản phẩm nổi bật</span>
        </label>
      </div>

      {{-- Buttons --}}
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
        <a href="{{ route('indexProduct') }}"
           class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
          Quay lại
        </a>
        <button type="submit"
                class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">
          Lưu sản phẩm
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
