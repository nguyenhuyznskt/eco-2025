@extends('admin.layouts.layout')

@section('content')

{{-- 🔔 Toast thông báo --}}
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
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">📦 Danh sách sản phẩm</h1>
      <p class="text-gray-500 mt-1 text-sm">Quản lý tất cả sản phẩm và thông tin cơ bản.</p>
    </div>

    <a href="{{ route('createProduct') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow hover:shadow-md hover:scale-[1.02] transition-all duration-200">
      <i class="bx bx-plus text-lg"></i>
      <span>Thêm sản phẩm</span>
    </a>
  </div>

  {{-- 🎯 Bộ lọc --}}
  <form method="GET"
        action="{{ route('indexProduct') }}"
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">

    {{-- Ô tìm kiếm --}}
    <div class="relative w-full md:w-1/3">
      <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Tìm theo tên sản phẩm..."
             class="w-full h-10 pl-9 pr-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
    </div>

    {{-- Danh mục --}}
    <select name="category_id"
            class="h-10 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">-- Tất cả danh mục --</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>

    {{-- Trạng thái --}}
    <select name="is_active"
            class="h-10 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">-- Trạng thái --</option>
      <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hiển thị</option>
      <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ẩn</option>
    </select>

    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition text-sm">
      <i class="bx bx-filter"></i> Lọc
    </button>

    <a href="{{ route('indexProduct') }}"
       class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
      <i class="bx bx-reset text-lg"></i> Đặt lại
    </a>
  </form>

  {{-- 📋 Bảng dữ liệu --}}
  <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200 mt-4">
    <table class="w-full text-sm text-left text-gray-700">
      <thead class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
        <tr>
          <th class="px-5 py-3 font-medium">ID</th>
          <th class="px-5 py-3 font-medium">Tên sản phẩm</th>
          <th class="px-5 py-3 font-medium">Danh mục</th>
          <th class="px-5 py-3 font-medium">Giá</th>
          <th class="px-5 py-3 font-medium">Trạng thái</th>
          <th class="px-5 py-3 font-medium">Ngày tạo</th>
          <th class="px-5 py-3 font-medium text-right">Hành động</th>
        </tr>
      </thead>

      <tbody>
        @forelse($products as $p)
        <tr class="hover:bg-indigo-50 transition-colors duration-150 even:bg-gray-50">
          <td class="px-5 py-3 font-semibold text-gray-800">{{ $p->id }}</td>
          <td class="px-5 py-3 text-gray-800 font-medium">{{ $p->name }}</td>
          <td class="px-5 py-3 text-gray-600 space-x-1">
            @foreach($p->categories as $cat)
              <span class="inline-block px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">{{ $cat->name }}</span>
            @endforeach
          </td>
          <td class="px-5 py-3 text-gray-700">{{ number_format($p->price, 0, ',', '.') }}đ</td>
          <td class="px-5 py-3">
            @if($p->is_active)
              <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">
                <i class="bx bx-show text-sm"></i> Hiển thị
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded-full font-medium">
                <i class="bx bx-hide text-sm"></i> Ẩn
              </span>
            @endif
          </td>
          <td class="px-5 py-3 text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
          <td class="px-5 py-3 text-right flex justify-end items-center gap-3">
            <a href="{{ route('editProduct', $p->id) }}"
               class="p-2 text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition" title="Chỉnh sửa">
              <i class="bx bx-edit-alt text-lg"></i>
            </a>
            <form action="{{ route('destroyProduct', $p->id) }}" method="POST"
                  onsubmit="return confirm('Xóa sản phẩm này?')">
              @csrf @method('DELETE')
              <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition" title="Xóa">
                <i class="bx bx-trash text-lg"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="py-8 text-center text-gray-500">
            <i class="bx bx-info-circle text-2xl mb-2 block"></i>
            <span>Không có sản phẩm nào.</span>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- 📄 Phân trang --}}
  <div class="flex justify-end mt-4">
    {{ $products->withQueryString()->links() }}
  </div>
</div>

{{-- 🎬 Script Toast --}}
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
