@extends('admin.layouts.layout')

@section('content')
<div class="p-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Danh sách thuộc tính</h1>
    <a href="{{ route('admin.attribute.create') }}" 
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
       + Thêm thuộc tính
    </a>
  </div>

  @if (session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full text-sm text-left border border-gray-200">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-4 py-3 border-b">#</th>
          <th class="px-4 py-3 border-b">Tên</th>
          <th class="px-4 py-3 border-b">Slug</th>
          <th class="px-4 py-3 border-b">Type</th>
          <th class="px-4 py-3 border-b text-center">Filterable</th>
          <th class="px-4 py-3 border-b text-center">Variation</th>
          <th class="px-4 py-3 border-b text-right">Hành động</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse ($data as $attr)
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2">{{ $attr->id }}</td>
          <td class="px-4 py-2 font-medium text-gray-900">{{ $attr->name }}</td>
          <td class="px-4 py-2 text-gray-600">{{ $attr->slug }}</td>
          <td class="px-4 py-2 text-gray-600">{{ ucfirst($attr->type ?? 'text') }}</td>
          <td class="px-4 py-2 text-center">
            @if($attr->is_filterable)
              <span class="text-green-600 font-semibold">✓</span>
            @else
              <span class="text-gray-400">✗</span>
            @endif
          </td>
          <td class="px-4 py-2 text-center">
            @if($attr->is_variation)
              <span class="text-blue-600 font-semibold">✓</span>
            @else
              <span class="text-gray-400">✗</span>
            @endif
          </td>
          <td class="px-4 py-2 text-right space-x-2">
            <a href="{{ route('admin.attribute.edit', $attr) }}" 
               class="text-blue-600 hover:underline">Sửa</a>
            <form action="{{ route('admin.attribute.destroy', $attr) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="text-red-600 hover:underline" 
                      onclick="return confirm('Xoá thuộc tính này?')">Xoá</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-gray-500 py-4">Chưa có thuộc tính nào</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $data->links() }}
  </div>
</div>
@endsection
