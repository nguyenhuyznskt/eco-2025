@extends('admin.layouts.layout')

@section('content')

{{-- Toast thông báo --}}
@if (session('success') || session('error'))
  <div id="flash-toast"
       class="fixed top-5 right-5 z-50 flex items-center gap-2 px-5 py-3 rounded-lg shadow-lg text-sm font-medium text-white
              {{ session('success') ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-600' }}">
    <i class="bx {{ session('success') ? 'bx-check-circle' : 'bx-x-circle' }} text-xl"></i>
    <span>{{ session('success') ?? session('error') }}</span>
  </div>
@endif

<div class="p-6 space-y-6">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">🧩 Thuộc tính sản phẩm</h1>
      <p class="text-gray-500 mt-1 text-sm">Quản lý và tuỳ chỉnh các thuộc tính hiển thị trong cửa hàng.</p>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('admin.attribute.create') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow hover:shadow-md hover:scale-[1.02] transition-all duration-200">
        <i class="bx bx-plus text-lg"></i>
        <span>Thêm mới</span>
      </a>

      <a href="{{ route('admin.attribute.trashed') }}"
         class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 bg-white font-medium rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
        <i class="bx bx-trash text-lg"></i>
        <span>Thùng rác</span>
      </a>
    </div>
  </div>

{{-- Bộ lọc nâng cao --}}
<form method="GET" action="{{ route('admin.attribute.index') }}" id="searchForm"
      class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">

  {{-- Nhập từ khóa --}}
  <div class="relative w-full md:w-1/3">
    <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
    <input type="text" name="search" id="liveSearch" value="{{ request('search') }}"
           placeholder="Tìm theo tên hoặc slug..."
           class="w-full h-10 pl-9 pr-9 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
    <button type="button" id="clearSearch"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg {{ request('search') ? '' : 'hidden' }}">
      <i class="bx bx-x"></i>
    </button>
  </div>

  {{-- Bộ lọc dropdown --}}
  <div class="flex flex-wrap gap-3 items-center w-full md:w-auto">

    {{-- Type --}}
    <select name="type" onchange="this.form.submit()"
            class="h-10 px-3 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">-- Loại thuộc tính --</option>
      <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Text</option>
      <option value="number" {{ request('type') == 'number' ? 'selected' : '' }}>Number</option>
      <option value="size" {{ request('type') == 'size' ? 'selected' : '' }}>Size</option>
      <option value="color" {{ request('type') == 'color' ? 'selected' : '' }}>Color</option>
      <option value="select" {{ request('type') == 'select' ? 'selected' : '' }}>Select</option>
    </select>

    {{-- Filterable --}}
    <select name="filterable" onchange="this.form.submit()"
            class="h-10 px-3 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">-- Bộ lọc --</option>
      <option value="1" {{ request('filterable') == '1' ? 'selected' : '' }}>✓ Có</option>
      <option value="0" {{ request('filterable') == '0' ? 'selected' : '' }}>✗ Không</option>
    </select>

    {{-- Variation --}}
    <select name="variation" onchange="this.form.submit()"
            class="h-10 px-3 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">-- Biến thể --</option>
      <option value="1" {{ request('variation') == '1' ? 'selected' : '' }}>✓ Có</option>
      <option value="0" {{ request('variation') == '0' ? 'selected' : '' }}>✗ Không</option>
    </select>

    {{-- Nút reset --}}
    <a href="{{ route('admin.attribute.index') }}"
       class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
      <i class="bx bx-reset text-lg"></i> Đặt lại
    </a>
  </div>

  {{-- Spinner --}}
  <div id="searchSpinner" class="hidden text-indigo-600 text-sm flex items-center gap-1">
    <i class="bx bx-loader-alt bx-spin text-lg"></i> <span>Đang tìm...</span>
  </div>
</form>





  {{-- Bảng dữ liệu --}}
  {{-- KHÔNG bọc bảng trong form nữa --}}
<div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200 mt-4">
  <table class="w-full text-sm text-left text-gray-700">
    <thead class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
      <tr>
        <th class="px-5 py-3">
          <input type="checkbox" id="selectAll" class="cursor-pointer w-4 h-4 text-indigo-600 rounded border-gray-300">
        </th>
        <th class="px-5 py-3 font-medium">Tên</th>
        <th class="px-5 py-3 font-medium">Slug</th>
        <th class="px-5 py-3 font-medium">Type</th>
        <th class="px-5 py-3 font-medium text-center">Filter</th>
        <th class="px-5 py-3 font-medium text-center">Variation</th>
        <th class="px-5 py-3 font-medium text-right">Hành động</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($data as $attr)
      <tr class="hover:bg-indigo-50 transition-colors duration-150 even:bg-gray-50">
        <td class="px-5 py-3">
          {{-- GÁN checkbox vào form bulk bằng thuộc tính form --}}
          <input form="bulkDeleteForm" type="checkbox" name="ids[]" value="{{ $attr->id }}"
                 class="rowCheckbox cursor-pointer w-4 h-4 text-indigo-600 rounded border-gray-300">
        </td>
        <td class="px-5 py-3 font-semibold text-gray-800">{{ $attr->name }}</td>
        <td class="px-5 py-3 text-gray-600">{{ $attr->slug ?? '-' }}</td>
        <td class="px-5 py-3 text-gray-600">{{ ucfirst($attr->type ?? 'text') }}</td>
        <td class="px-5 py-3 text-center">
          <i class="bx {{ $attr->is_filterable ? 'bx-check text-green-500' : 'bx-x text-gray-400' }} text-lg"></i>
        </td>
        <td class="px-5 py-3 text-center">
          <i class="bx {{ $attr->is_variation ? 'bx-check text-blue-500' : 'bx-x text-gray-400' }} text-lg"></i>
        </td>
        <td class="px-5 py-3 text-right flex justify-end items-center gap-3">
          <a href="{{ route('admin.attribute.edit', $attr) }}"
             title="Chỉnh sửa"
             class="p-2 text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition">
            <i class="bx bx-edit-alt text-lg"></i>
          </a>

          {{-- Form XOÁ ĐƠN LẺ: form riêng, KHÔNG bị lồng --}}
          <form action="{{ route('admin.attribute.destroy', $attr) }}" method="POST"
                onsubmit="return confirm('Xóa thuộc tính {{ $attr->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" title="Xóa" class="p-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
              <i class="bx bx-trash text-lg"></i>
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-8 text-gray-500">
          <i class="bx bx-info-circle text-2xl mb-2 block"></i>
          <span>Không có thuộc tính nào</span>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- FORM BULK tách riêng ở cuối, KHÔNG bọc bảng --}}
<form method="POST" action="{{ route('admin.attribute.bulkDelete') }}" id="bulkDeleteForm">
  @csrf
  @method('DELETE')
  {{-- không cần input ids ở đây, vì checkbox đã có form="bulkDeleteForm" --}}
</form>

{{-- Thanh nút dưới cùng --}}
<div class="flex justify-between items-center mt-4">
  <button type="submit" form="bulkDeleteForm"
          class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 disabled:opacity-50 transition text-sm"
          id="bulkDeleteBtn" disabled>
    <i class="bx bx-trash"></i>
    Xóa đã chọn
  </button>
  <div>
    {{ $data->links() }}
  </div>
</div>
</div>

{{-- Script --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // --- Checkbox chọn hàng loạt ---
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.rowCheckbox');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
  
    function updateButton() {
      const checked = document.querySelectorAll('.rowCheckbox:checked').length;
      if (bulkBtn) bulkBtn.disabled = checked === 0;
    }
  
    if (selectAll) {
      selectAll.addEventListener('change', e => {
        checkboxes.forEach(cb => (cb.checked = e.target.checked));
        updateButton();
      });
      checkboxes.forEach(cb => cb.addEventListener('change', updateButton));
    }
  
    // --- Hiệu ứng toast ---
    const toast = document.getElementById('flash-toast');
    if (toast) {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
      }, 100);
      setTimeout(() => {
        toast.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 600);
      }, 4000);
    }
  
    // --- Live search ---
    const searchInput = document.getElementById('liveSearch');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('searchForm');
    const spinner = document.getElementById('searchSpinner');
    let typingTimer;
    const delay = 500; // 0.5s sau khi ngừng gõ
  
    if (searchInput && form) {
      searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
  
        // Hiện hoặc ẩn nút ❌
        if (clearBtn) clearBtn.classList.toggle('hidden', searchInput.value.trim() === '');
  
        // Hiện spinner khi gõ
        spinner?.classList.remove('hidden');
  
        // Delay 0.5s rồi submit
        typingTimer = setTimeout(() => {
          spinner?.classList.add('hidden'); // ẩn spinner trước khi submit
          form.submit(); // tự động gửi form
        }, delay);
      });
  
      searchInput.addEventListener('keydown', () => {
        clearTimeout(typingTimer);
      });
    }
  
    // --- Nút xóa text trong input ---
    if (clearBtn && searchInput && form) {
      clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        form.submit(); // reload lại danh sách gốc
      });
    }
  });
  </script>
  
  
  
@endsection
