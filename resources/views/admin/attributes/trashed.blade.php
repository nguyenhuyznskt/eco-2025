@extends('admin.layouts.layout')

@section('content')
<div class="p-6 space-y-6">

  {{-- Toast thông báo --}}
  @if (session('success') || session('error'))
    <div id="flash-toast"
         class="fixed top-5 right-5 z-50 flex items-center gap-2 px-5 py-3 rounded-lg shadow-lg text-sm font-medium text-white
                {{ session('success') ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-600' }}">
      <i class="bx {{ session('success') ? 'bx-check-circle' : 'bx-x-circle' }} text-xl"></i>
      <span>{{ session('success') ?? session('error') }}</span>
    </div>
  @endif

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">🗑️ Thùng rác thuộc tính</h1>
      <p class="text-gray-500 text-sm mt-1">Quản lý các thuộc tính đã xoá. Có thể khôi phục hoặc xoá vĩnh viễn.</p>
    </div>

    <div class="flex gap-3">
      {{-- Xoá toàn bộ --}}
      <form action="{{ route('admin.attribute.forceDeleteAll') }}" method="POST"
            onsubmit="return confirm('⚠️ Xoá vĩnh viễn TẤT CẢ trong thùng rác?')">
        @csrf @method('DELETE')
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200">
          <i class="bx bx-trash text-lg"></i> <span>Xoá toàn bộ</span>
        </button>
      </form>

      <a href="{{ route('admin.attribute.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-lg shadow-sm hover:shadow-md hover:from-gray-200 hover:to-gray-300 transition-all duration-200">
        <i class="bx bx-left-arrow-alt text-lg"></i> <span>Quay lại</span>
      </a>
    </div>
  </div>

  {{-- Bảng + chọn nhiều --}}
  <form id="bulkForm" method="POST">
    @csrf
    <input type="hidden" name="_method" id="bulkMethod" value="POST">

    <div class="overflow-hidden bg-white shadow-md rounded-xl border border-gray-200 mt-4">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 border-b text-gray-600 uppercase text-xs font-medium">
          <tr>
            <th class="px-5 py-3">
              <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
            </th>
            <th class="px-5 py-3">Tên thuộc tính</th>
            <th class="px-5 py-3">Loại</th>
            <th class="px-5 py-3">Ngày xoá</th>
            <th class="px-5 py-3 text-right">Hành động</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse ($data as $attr)
            <tr class="hover:bg-indigo-50 transition-colors duration-150">
              <td class="px-5 py-3">
                <input type="checkbox" name="ids[]" value="{{ $attr->id }}" class="rowCheckbox w-4 h-4 text-indigo-600 border-gray-300 rounded">
              </td>
              <td class="px-5 py-3 font-semibold text-gray-800">{{ $attr->name }}</td>
              <td class="px-5 py-3 text-gray-600">{{ ucfirst($attr->type ?? '-') }}</td>
              <td class="px-5 py-3 text-gray-500 text-sm">
                {{ $attr->deleted_at ? $attr->deleted_at->format('d/m/Y H:i') : '-' }}
              </td>
              <td class="px-5 py-3 text-right">
                <div class="flex justify-end gap-2">
                  {{-- Restore đơn lẻ --}}
                  <form action="{{ route('admin.attribute.restore', ['id' => $attr->id]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 hover:scale-[1.02] transition">
                      <i class="bx bx-undo"></i> Khôi phục
                    </button>
                  </form>

                  {{-- Force delete đơn lẻ --}}
                  <form action="{{ route('admin.attribute.forceDelete', ['id' => $attr->id]) }}" method="POST"
                        onsubmit="return confirm('Xoá vĩnh viễn {{ $attr->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:scale-[1.02] transition">
                      <i class="bx bx-trash"></i> Xoá hẳn
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-10 text-center text-gray-500">
                <i class="bx bx-info-circle text-3xl mb-2 block"></i>
                <p>Không có thuộc tính nào trong thùng rác</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Thanh hành động hàng loạt --}}
    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <button type="button" id="bulkRestoreBtn"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 disabled:opacity-50 transition text-sm"
                disabled
                data-action="{{ route('admin.attribute.bulkRestore') }}"
                data-method="POST">
          <i class="bx bx-undo"></i> Khôi phục đã chọn
        </button>

        <button type="button" id="bulkForceBtn"
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 disabled:opacity-50 transition text-sm"
                disabled
                data-action="{{ route('admin.attribute.bulkForceDelete') }}"
                data-method="DELETE"
                onclick="return confirm('Xoá vĩnh viễn các thuộc tính đã chọn?')">
          <i class="bx bx-trash"></i> Xoá đã chọn
        </button>
      </div>

      <div>{{ $data->links() }}</div>
    </div>
  </form>
</div>

{{-- Script: toast + chọn nhiều + bulk action --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Toast
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

  // Chọn nhiều
  const selectAll = document.getElementById('selectAll');
  const checkboxes = document.querySelectorAll('.rowCheckbox');
  const bulkRestoreBtn = document.getElementById('bulkRestoreBtn');
  const bulkForceBtn = document.getElementById('bulkForceBtn');

  function updateBulkState() {
    const checked = document.querySelectorAll('.rowCheckbox:checked').length;
    bulkRestoreBtn.disabled = checked === 0;
    bulkForceBtn.disabled = checked === 0;
  }

  selectAll?.addEventListener('change', e => {
    checkboxes.forEach(cb => cb.checked = e.target.checked);
    updateBulkState();
  });
  checkboxes.forEach(cb => cb.addEventListener('change', updateBulkState));

  // Gửi bulk form với action/method tương ứng
  const bulkForm = document.getElementById('bulkForm');
  const bulkMethod = document.getElementById('bulkMethod');

  function submitBulk(btn) {
    const action = btn.getAttribute('data-action');
    const method = btn.getAttribute('data-method') || 'POST';
    bulkForm.setAttribute('action', action);
    bulkMethod.value = method; // đổi _method khi cần (DELETE)
    bulkForm.submit();
  }

  bulkRestoreBtn?.addEventListener('click', () => submitBulk(bulkRestoreBtn));
  bulkForceBtn?.addEventListener('click', () => submitBulk(bulkForceBtn));
});
</script>
@endsection
