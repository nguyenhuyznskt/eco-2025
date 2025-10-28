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
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">🎨 Giá trị thuộc tính</h1>
      <p class="text-gray-500 mt-1 text-sm">Quản lý các giá trị con của từng thuộc tính sản phẩm.</p>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('admin.attribute_value.create') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow hover:shadow-md hover:scale-[1.02] transition-all duration-200">
        <i class="bx bx-plus text-lg"></i>
        <span>Thêm mới</span>
      </a>

      <a href="{{ route('admin.attribute_value.trashed') }}"
         class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 bg-white font-medium rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
        <i class="bx bx-trash text-lg"></i>
        <span>Thùng rác</span>
      </a>
    </div>
  </div>

  {{-- Bộ lọc --}}
  <form method="GET" action="{{ route('admin.attribute_value.index') }}" id="searchForm"
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">

    {{-- Ô tìm kiếm --}}
    <div class="relative w-full md:w-1/3">
      <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
      <input type="text" name="search" id="liveSearch" value="{{ request('search') }}"
             placeholder="Tìm value hoặc label..."
             class="w-full h-10 pl-9 pr-9 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
      <button type="button" id="clearSearch"
              class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg {{ request('search') ? '' : 'hidden' }}">
        <i class="bx bx-x"></i>
      </button>
    </div>

    {{-- Checkbox hiển thị đã xóa --}}
    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
      <input type="checkbox" name="with_trashed" value="1"
             onchange="this.form.submit()"
             {{ request('with_trashed') ? 'checked' : '' }}
             class="w-4 h-4 text-indigo-600 rounded border-gray-300">
      <span>Hiển thị đã xoá</span>
    </label>

    <a href="{{ route('admin.attribute_value.index') }}"
       class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
      <i class="bx bx-reset text-lg"></i> Đặt lại
    </a>

    <div id="searchSpinner" class="hidden text-indigo-600 text-sm flex items-center gap-1">
      <i class="bx bx-loader-alt bx-spin text-lg"></i> <span>Đang tìm...</span>
    </div>
  </form>

  {{-- Bảng dữ liệu --}}
  <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200 mt-4">
    <table class="w-full text-sm text-left text-gray-700">
      <thead class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
        <tr>
          <th class="px-5 py-3"><input type="checkbox" id="selectAll" class="cursor-pointer w-4 h-4 text-indigo-600 rounded border-gray-300"></th>
          <th class="px-5 py-3 font-medium">ID</th>
          <th class="px-5 py-3 font-medium">Thuộc tính</th>
          <th class="px-5 py-3 font-medium">Value</th>
          <th class="px-5 py-3 font-medium">Label</th>
          <th class="px-5 py-3 font-medium text-center">Thứ tự</th>
          <th class="px-5 py-3 font-medium text-right">Hành động</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($data as $item)
        <tr class="hover:bg-indigo-50 transition-colors duration-150 even:bg-gray-50">
          <td class="px-5 py-3">
            <input form="bulkDeleteForm" type="checkbox" name="ids[]" value="{{ $item->id }}"
                   class="rowCheckbox cursor-pointer w-4 h-4 text-indigo-600 rounded border-gray-300">
          </td>
          <td class="px-5 py-3 font-semibold text-gray-800">{{ $item->id }}</td>
          <td class="px-5 py-3 text-gray-600">{{ $item->attribute->name ?? '-' }}</td>
          <td class="px-5 py-3 text-gray-700 font-medium">{{ $item->value }}</td>
          <td class="px-5 py-3 text-gray-600">{{ $item->label ?? '-' }}</td>
          <td class="px-5 py-3 text-center">{{ $item->sort_order ?? '-' }}</td>
          <td class="px-5 py-3 text-right flex justify-end items-center gap-3">
            <a href="{{ route('admin.attribute_value.edit', $item) }}"
               title="Chỉnh sửa"
               class="p-2 text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition">
              <i class="bx bx-edit-alt text-lg"></i>
            </a>

            <form action="{{ route('admin.attribute_value.destroy', $item) }}" method="POST"
                  onsubmit="return confirm('Xóa giá trị này?')">
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
            <span>Không có giá trị nào</span>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Form xóa hàng loạt --}}
  <form method="POST" action="{{ route('admin.attribute_value.bulk-delete') }}" id="bulkDeleteForm">
    @csrf
  </form>

  {{-- Thanh công cụ cuối --}}
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
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.rowCheckbox');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    const toast = document.getElementById('flash-toast');
    const searchInput = document.getElementById('liveSearch');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('searchForm');
    const spinner = document.getElementById('searchSpinner');
    let typingTimer;

    // --- chọn hàng loạt ---
    function updateButton() {
      bulkBtn.disabled = document.querySelectorAll('.rowCheckbox:checked').length === 0;
    }
    if (selectAll) {
  selectAll.addEventListener('change', e => {
    checkboxes.forEach(cb => cb.checked = e.target.checked);
    updateButton();
  });

  checkboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      updateButton();
      // ✅ Nếu tất cả checkbox con đều được chọn, thì tự tick “chọn tất cả”
      const allChecked = [...checkboxes].every(c => c.checked);
      selectAll.checked = allChecked;
    });
  });
}


    // --- toast ---
    if (toast) {
      setTimeout(() => {
        toast.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 600);
      }, 4000);
    }

    // --- live search ---
    const delay = 500;
    if (searchInput && form) {
      searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        clearBtn?.classList.toggle('hidden', searchInput.value.trim() === '');
        spinner?.classList.remove('hidden');
        typingTimer = setTimeout(() => {
          spinner?.classList.add('hidden');
          form.submit();
        }, delay);
      });
      searchInput.addEventListener('keydown', () => clearTimeout(typingTimer));
    }

    if (clearBtn && searchInput && form) {
      clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        form.submit();
      });
    }
  });
</script>

@endsection
