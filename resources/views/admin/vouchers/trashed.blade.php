@extends('admin.layouts.layout')
@section('content')
<div class="p-6 space-y-4">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">🗑️ Thùng rác Voucher</h1>
    <a href="{{ route('admin.voucher.index') }}" class="px-3 py-2 bg-gray-200 rounded-lg">⬅ Quay lại</a>
  </div>

  <form method="POST" action="{{ route('admin.voucher.bulk-restore') }}">
    @csrf
    <table class="w-full bg-white shadow rounded-xl overflow-hidden">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3"><input type="checkbox" id="select-all"></th>
          <th class="p-3 text-left">Mã</th>
          <th class="p-3 text-left">Loại</th>
          <th class="p-3 text-left">Giá trị</th>
          <th class="p-3 text-left">Ngày xóa</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $item)
          <tr class="border-t hover:bg-gray-50">
            <td class="p-3"><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
            <td class="p-3 font-medium">{{ $item->code }}</td>
            <td class="p-3">{{ $item->type }}</td>
            <td class="p-3">{{ $item->value }}</td>
            <td class="p-3 text-sm text-gray-500">{{ $item->deleted_at?->format('d/m/Y H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="p-4 text-center text-gray-500">Không có voucher nào trong thùng rác</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="flex justify-between mt-4">
      <div class="space-x-2">
        <button formaction="{{ route('admin.voucher.bulk-restore') }}"
                class="px-3 py-2 bg-green-600 text-white rounded-lg"
                onclick="return confirm('Khôi phục các voucher đã chọn?')">
          Khôi phục
        </button>

        <button formaction="{{ route('admin.voucher.bulk-force-delete') }}"
                formmethod="POST"
                onclick="return confirm('Xóa vĩnh viễn các voucher đã chọn?')"
                class="px-3 py-2 bg-red-600 text-white rounded-lg">
          Xóa vĩnh viễn
        </button>

        {{-- Laravel cần hidden method DELETE --}}
        <input type="hidden" name="_method" value="DELETE">
      </div>

      {{ $data->links() }}
    </div>
  </form>
</div>

<script>
  // ✅ Checkbox All logic 2 chiều (chuẩn)
  const selectAll = document.getElementById('select-all');
  const itemCheckboxes = document.querySelectorAll('input[name="ids[]"]');

  // Khi click chọn All
  selectAll.addEventListener('change', e => {
    itemCheckboxes.forEach(cb => cb.checked = e.target.checked);
  });

  // Khi click từng checkbox con
  itemCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
      const noneChecked = Array.from(itemCheckboxes).every(c => !c.checked);

      if (allChecked) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
      } else if (noneChecked) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
      } else {
        // trạng thái trung gian
        selectAll.indeterminate = true;
      }
    });
  });
</script>
@endsection
