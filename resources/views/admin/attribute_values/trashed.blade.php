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
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">🗑️ Thùng rác giá trị thuộc tính</h1>
      <p class="text-gray-500 mt-1 text-sm">Quản lý các giá trị đã xóa mềm — khôi phục hoặc xóa vĩnh viễn.</p>
    </div>

    <a href="{{ route('admin.attribute_value.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all duration-200">
      <i class="bx bx-arrow-back text-lg"></i>
      <span>Quay lại danh sách</span>
    </a>
  </div>

  {{-- FORM BULK --}}
  <form id="bulkForm" method="POST" class="space-y-3">
    @csrf
    <input type="hidden" name="_method" id="bulkMethod" value="POST">

    {{-- Nút bulk --}}
    <div class="flex gap-3">
      {{-- Khôi phục đã chọn --}}
      <button type="button" id="bulkRestoreBtn"
              class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 disabled:opacity-50 transition text-sm"
              disabled
              data-action="{{ route('admin.attribute_value.bulk-restore') }}"
              data-method="POST">
        <i class="bx bx-undo"></i> Khôi phục đã chọn
      </button>

      {{-- Xóa vĩnh viễn --}}
      <button type="button" id="bulkDeleteBtn"
              class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 disabled:opacity-50 transition text-sm"
              disabled
              data-action="{{ route('admin.attribute_value.bulk-force-delete') }}"
              data-method="DELETE">
        <i class="bx bx-trash"></i> Xóa vĩnh viễn
      </button>
    </div>

    {{-- Bảng --}}
    <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
          <tr>
            <th class="px-5 py-3">
              <input type="checkbox" id="selectAll"
                     class="cursor-pointer w-4 h-4 text-indigo-600 rounded border-gray-300">
            </th>
            <th class="px-5 py-3 font-medium">ID</th>
            <th class="px-5 py-3 font-medium">Thuộc tính</th>
            <th class="px-5 py-3 font-medium">Value</th>
            <th class="px-5 py-3 font-medium">Label</th>
            <th class="px-5 py-3 font-medium text-center">Đã xóa lúc</th>
            <th class="px-5 py-3 font-medium text-right">Hành động</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($data as $item)
            <tr class="hover:bg-indigo-50 transition-all duration-150 even:bg-gray-50">
              <td class="px-5 py-3">
                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                       class="rowCheckbox w-4 h-4 text-indigo-600 rounded border-gray-300 cursor-pointer">
              </td>
              <td class="px-5 py-3 font-semibold text-gray-800">{{ $item->id }}</td>
              <td class="px-5 py-3 text-gray-600">{{ $item->attribute->name ?? '-' }}</td>
              <td class="px-5 py-3 text-gray-800 font-medium">{{ $item->value }}</td>
              <td class="px-5 py-3 text-gray-600">{{ $item->label ?? '-' }}</td>
              <td class="px-5 py-3 text-center text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
              <td class="px-5 py-3 flex justify-end items-center gap-2">
                {{-- Khôi phục --}}
                <form action="{{ route('admin.attribute_value.restore', $item->id) }}" method="POST"
                      onsubmit="return confirm('Khôi phục giá trị này?')">
                  @csrf
                  <button type="submit" title="Khôi phục"
                          class="p-2 text-emerald-600 bg-emerald-50 rounded-md hover:bg-emerald-100 transition">
                    <i class="bx bx-undo text-lg"></i>
                  </button>
                </form>
                {{-- Xóa vĩnh viễn --}}
                <form action="{{ route('admin.attribute_value.force-delete', $item->id) }}" method="POST"
                      onsubmit="return confirm('Xóa vĩnh viễn giá trị này?')">
                  @csrf @method('DELETE')
                  <button type="submit" title="Xóa vĩnh viễn"
                          class="p-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
                    <i class="bx bx-trash text-lg"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-gray-500">
                <i class="bx bx-info-circle text-2xl mb-2 block"></i>
                <span>Không có giá trị nào trong thùng rác</span>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="flex justify-end">
      {{ $data->links() }}
    </div>
  </form>
</div>

{{-- 🎬 Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectAll   = document.getElementById('selectAll');
  const bulkForm    = document.getElementById('bulkForm');
  const bulkMethod  = document.getElementById('bulkMethod');
  const restoreBtn  = document.getElementById('bulkRestoreBtn');
  const deleteBtn   = document.getElementById('bulkDeleteBtn');

  const getRowChecks = () => Array.from(document.querySelectorAll('.rowCheckbox'));
  const getChecked   = () => getRowChecks().filter(cb => cb.checked);

  function updateButtonsAndMaster() {
    const rows = getRowChecks();
    const checked = getChecked().length;
    // enable/disable buttons
    const hasAny = checked > 0;
    restoreBtn.disabled = !hasAny;
    deleteBtn.disabled  = !hasAny;

    // sync master checkbox (fix: nếu tick hết thì ô master cũng tick)
    const total = rows.length;
    if (total === 0) {
      selectAll.checked = false;
      selectAll.indeterminate = false;
      return;
    }
    if (checked === total) {
      selectAll.checked = true;
      selectAll.indeterminate = false;
    } else if (checked === 0) {
      selectAll.checked = false;
      selectAll.indeterminate = false;
    } else {
      // trạng thái “một phần”
      selectAll.checked = false;
      selectAll.indeterminate = true;
    }
  }

  // master toggle
  selectAll?.addEventListener('change', e => {
    getRowChecks().forEach(cb => cb.checked = e.target.checked);
    updateButtonsAndMaster();
  });

  // row toggle
  getRowChecks().forEach(cb => cb.addEventListener('change', updateButtonsAndMaster));

  // submit bulk
  function submitBulk(btn) {
  const action = btn.dataset.action;
  const method = btn.dataset.method || 'POST';
  const ids = getChecked().map(cb => cb.value);
  if (!ids.length) return;

  // Xóa hidden input cũ
  bulkForm.querySelectorAll('input[name="ids[]"].ghost').forEach(el => el.remove());

  // Thêm input mới
  ids.forEach(id => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = id;
    input.classList.add('ghost');
    bulkForm.appendChild(input);
  });

  // Cập nhật action và _method đúng chuẩn Laravel
  bulkForm.action = action;
  bulkMethod.value = (method.toUpperCase() === 'DELETE') ? 'DELETE' : 'POST';
  console.log('Submitting to:', bulkForm.action, 'method:', bulkMethod.value);
  bulkForm.submit();
}


  restoreBtn?.addEventListener('click', () => submitBulk(restoreBtn));
  deleteBtn?.addEventListener('click', () => {
    if (!confirm('Xóa vĩnh viễn các giá trị đã chọn?')) return;
    submitBulk(deleteBtn);
  });

  // toast auto-hide
  const toast = document.getElementById('flash-toast');
  if (toast) {
    setTimeout(() => {
      toast.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => toast.remove(), 600);
    }, 4000);
  }

  // khởi tạo trạng thái ban đầu
  updateButtonsAndMaster();
});
</script>
@endsection
