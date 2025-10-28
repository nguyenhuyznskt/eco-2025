@extends('admin.layouts.layout')

@section('content')
<div class="p-6">
  <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">✏️ Sửa sản phẩm</h1>

    <form action="{{ route('updateProduct', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @method('PUT')

      {{-- Tên và Slug --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name', $product->name) }}"
                 class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Slug</label>
          <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                 class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      {{-- Danh mục --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Danh mục</label>
        <select name="category_ids[]" multiple
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-32">
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ $product->categories->contains($cat->id) ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
        <p class="text-gray-500 text-sm mt-1">Giữ Ctrl (hoặc Cmd) để chọn nhiều danh mục</p>
      </div>

      {{-- Giá --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Giá bán <span class="text-red-500">*</span></label>
          <input type="number" name="price" value="{{ old('price', $product->price) }}"
                 class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
          <input type="number" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}"
                 class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      {{-- Mô tả --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
        <textarea name="short_description" rows="2"
                  class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('short_description', $product->short_description) }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
        <textarea name="description" rows="5"
                  class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $product->description) }}</textarea>
      </div>

      {{-- Ảnh sản phẩm --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Ảnh sản phẩm</label>
        <input type="file" name="image" accept="image/*"
               class="mt-1 w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:ring-indigo-500 focus:border-indigo-500">
        @if($product->image)
          <div class="mt-3">
            <p class="text-sm text-gray-600 mb-1">Ảnh hiện tại:</p>
            <img src="{{ asset('storage/'.$product->image) }}" alt="Product image"
                 class="w-28 h-28 object-cover border rounded-md shadow-sm">
          </div>
        @endif
      </div>

      {{-- Checkbox --}}
      <div class="flex gap-6 mt-4">
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                 class="rounded text-indigo-600 focus:ring-indigo-500">
          <span class="text-sm text-gray-700">Hiển thị</span>
        </label>

        <label class="flex items-center space-x-2">
          <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                 class="rounded text-indigo-600 focus:ring-indigo-500">
          <span class="text-sm text-gray-700">Sản phẩm nổi bật</span>
        </label>
      </div>

      {{-- Nút --}}
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
        <a href="{{ route('indexProduct') }}"
           class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
          Quay lại
        </a>
        <button type="submit"
                class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">
          Cập nhật
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
