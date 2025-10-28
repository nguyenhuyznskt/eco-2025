@extends('admin.layouts.layout')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
  <h1 class="text-2xl font-semibold mb-4 text-gray-800">Thêm thuộc tính mới</h1>

  <form action="{{ route('admin.attribute.store') }}" method="POST" class="space-y-5 bg-white p-6 rounded-lg shadow">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700">Tên thuộc tính</label>
      <input type="text" name="name" value="{{ old('name') }}"
             class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
      @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Slug (tuỳ chọn)</label>
      <input type="text" name="slug" value="{{ old('slug') }}"
             class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Loại</label>
      <select name="type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @foreach(['text','select','color','size','number'] as $type)
          <option value="{{ $type }}">{{ ucfirst($type) }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex gap-4 mt-4">
      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_filterable" value="1" checked class="rounded text-blue-600">
        <span class="text-gray-700 text-sm">Hiển thị trong bộ lọc</span>
      </label>
      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_variation" value="1" class="rounded text-blue-600">
        <span class="text-gray-700 text-sm">Thuộc tính biến thể</span>
      </label>
    </div>

    <div class="flex justify-end mt-6">
      <a href="{{ route('admin.attribute.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 mr-2">Huỷ</a>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
    </div>
  </form>
</div>
@endsection
