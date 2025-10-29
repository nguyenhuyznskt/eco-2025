@extends('admin.layouts.layout')
@section('content')
<div class="p-6 space-y-4" x-data="{ showToast: false, toastMessage: '' }" x-init="
  @if(session('success'))
    showToast = true;
    toastMessage = '{{ session('success') }}';
    setTimeout(() => showToast = false, 3000);
  @endif
">
  {{-- 🔔 Toast thông báo --}}
  <div
    x-show="showToast"
    x-transition
    class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50"
  >
    <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-white' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
      <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' />
    </svg>
    <span x-text="toastMessage"></span>
  </div>

  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">🎟️ Danh sách Voucher</h1>
    <div class="flex gap-2">
      <a href="{{ route('admin.voucher.trashed') }}" class="px-3 py-2 bg-gray-200 rounded-lg">Thùng rác</a>
      <a href="{{ route('admin.voucher.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg">Thêm mới</a>
    </div>
  </div>

  {{-- Bộ lọc --}}
  <form id="filterForm" method="GET" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Từ khóa</label>
        <input type="text" name="keyword" value="{{ request('keyword') }}"
               placeholder="Nhập mã voucher..."
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Loại giảm giá</label>
        <select name="type"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
          <option value="">-- Tất cả --</option>
          <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Cố định (₫)</option>
          <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
        </select>
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Trạng thái</label>
        <select name="status"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
          <option value="">-- Tất cả --</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Kích hoạt</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tắt</option>
        </select>
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Hiệu lực</label>
        <select name="validity"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
          <option value="">-- Tất cả --</option>
          <option value="active" {{ request('validity') === 'active' ? 'selected' : '' }}>Còn hiệu lực</option>
          <option value="expired" {{ request('validity') === 'expired' ? 'selected' : '' }}>Hết hạn</option>
          <option value="upcoming" {{ request('validity') === 'upcoming' ? 'selected' : '' }}>Chưa bắt đầu</option>
        </select>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 pt-2">
      <a href="{{ route('admin.voucher.index') }}"
         class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm text-gray-700 rounded-lg">Xóa lọc</a>
    </div>
  </form>

  {{-- Form Bulk Delete --}}
  <form id="bulkDeleteForm" method="POST" action="{{ route('admin.voucher.bulk-delete') }}">
    @csrf
    <table class="w-full border-collapse bg-white shadow rounded-xl overflow-hidden mt-4">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 text-left"><input type="checkbox" id="select-all"></th>
          <th class="p-3 text-left">Mã</th>
          <th class="p-3 text-left">Loại</th>
          <th class="p-3 text-left">Giá trị</th>
          <th class="p-3 text-left">Đơn tối thiểu</th>
          <th class="p-3 text-left">Hiệu lực</th>
          <th class="p-3 text-left">Trạng thái</th>
          <th class="p-3 text-right">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $item)
          <tr class="border-t hover:bg-gray-50">
            <td class="p-3"><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
            <td class="p-3 font-medium">{{ $item->code }}</td>
            <td class="p-3">{{ $item->type }}</td>
            <td class="p-3">
              {{ $item->type === 'percent' ? $item->value . '%' : number_format($item->value) . '₫' }}
            </td>
            <td class="p-3">{{ number_format($item->min_order_amount) }}₫</td>
            <td class="p-3 text-sm text-gray-500">
              {{ $item->start_at ? $item->start_at->format('d/m/Y') : '-' }} →
              {{ $item->end_at ? $item->end_at->format('d/m/Y') : '-' }}
            </td>
            <td class="p-3">
              @if($item->is_active)
                <span class="text-green-600 font-semibold">Kích hoạt</span>
              @else
                <span class="text-gray-500">Tắt</span>
              @endif
            </td>
            <td class="p-3 text-right space-x-2">
              <a href="{{ route('admin.voucher.edit', $item) }}" class="text-blue-600 hover:underline">Sửa</a>
              <button type="button" class="text-red-600 hover:underline"
                      onclick="deleteVoucher('{{ route('admin.voucher.destroy', $item) }}')">Xóa</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="p-4 text-center text-gray-500">Không có dữ liệu</td></tr>
        @endforelse
      </tbody>
    </table>

    <div class="flex justify-between mt-4">
      <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg"
              onclick="return confirm('Xóa các voucher đã chọn?')">
        Xóa đã chọn
      </button>
      {{ $data->links() }}
    </div>
  </form>
</div>

<script>
  // Checkbox Select All
  const selectAll = document.getElementById('select-all');
  const itemCheckboxes = document.querySelectorAll('input[name="ids[]"]');
  selectAll.addEventListener('change', e => itemCheckboxes.forEach(cb => cb.checked = e.target.checked));
  itemCheckboxes.forEach(cb => cb.addEventListener('change', () => {
    const allChecked = [...itemCheckboxes].every(c => c.checked);
    const noneChecked = [...itemCheckboxes].every(c => !c.checked);
    selectAll.checked = allChecked;
    selectAll.indeterminate = !allChecked && !noneChecked;
  }));

  // Auto Filter
  const filterForm = document.getElementById('filterForm');
  const inputs = filterForm.querySelectorAll('input[name], select[name]');
  let debounceTimer;
  const debounce = (callback, delay = 400) => { clearTimeout(debounceTimer); debounceTimer = setTimeout(callback, delay); };
  inputs.forEach(el => {
    if (el.tagName === 'INPUT' && el.type === 'text') el.addEventListener('input', () => debounce(() => filterForm.submit()));
    else el.addEventListener('change', () => filterForm.submit());
  });

  // Xóa từng voucher bằng JS
  function deleteVoucher(url) {
    if (!confirm('Xóa voucher này?')) return;
    const f = document.createElement('form');
    f.method = 'POST';
    f.action = url;
    f.innerHTML = `@csrf @method('DELETE')`;
    document.body.appendChild(f);
    f.submit();
  }
</script>
@endsection
