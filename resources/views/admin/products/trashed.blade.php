@extends('admin.layouts.layout')
@section('content')
<div class="p-6 space-y-4">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">🗑️ Thùng rác sản phẩm</h1>
      <p class="text-gray-500 text-sm">Khôi phục hoặc xóa vĩnh viễn.</p>
    </div>
    <a href="{{ route('admin.product.index') }}" class="px-3 py-2 bg-gray-200 rounded-lg">Quay lại danh sách</a>
  </div>

  <form id="bulkForm" method="POST" class="space-y-2">
    @csrf <input type="hidden" name="_method" id="bulkMethod" value="POST">
    <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="px-4 py-3"><input id="selectAll" type="checkbox" class="w-4 h-4"></th>
            <th class="px-4 py-3 text-left">Tên</th>
            <th class="px-4 py-3">Slug</th>
            <th class="px-4 py-3">Đã xóa lúc</th>
            <th class="px-4 py-3 text-right">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $row)
          <tr class="border-t">
            <td class="px-4 py-3"><input class="rowCheckbox w-4 h-4" type="checkbox" value="{{ $row->id }}"></td>
            <td class="px-4 py-3 font-medium">{{ $row->name }}</td>
            <td class="px-4 py-3 text-center">{{ $row->slug }}</td>
            <td class="px-4 py-3 text-center">{{ $row->deleted_at->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <form action="{{ route('admin.product.restore',$row->id) }}" method="POST" onsubmit="return confirm('Khôi phục?')">
                  @csrf <button class="px-2 py-1 bg-emerald-600 text-white rounded-lg">Khôi phục</button>
                </form>
                <form action="{{ route('admin.product.force-delete',$row->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn?')">
                  @csrf @method('DELETE')
                  <button class="px-2 py-1 bg-red-600 text-white rounded-lg">Xóa vĩnh viễn</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Trống</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="flex items-center justify-between">
      <div class="flex gap-2">
        <button type="button" id="bulkRestoreBtn"
                data-action="{{ route('admin.product.bulk-restore') }}" data-method="POST"
                class="px-3 py-2 bg-emerald-600 text-white rounded-lg disabled:opacity-50" disabled>Khôi phục đã chọn</button>
        <button type="button" id="bulkDeleteBtn"
                data-action="{{ route('admin.product.bulk-force-delete') }}" data-method="DELETE"
                class="px-3 py-2 bg-red-600 text-white rounded-lg disabled:opacity-50" disabled>Xóa vĩnh viễn</button>
        <form action="{{ route('admin.product.force-delete-all') }}" method="POST" onsubmit="return confirm('Xóa sạch thùng rác?')">
          @csrf @method('DELETE')
          <button class="px-3 py-2 bg-red-100 rounded-lg">Xóa hết</button>
        </form>
      </div>
      <div>{{ $data->links() }}</div>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  const selectAll = document.getElementById('selectAll');
  const bulkForm  = document.getElementById('bulkForm');
  const bulkMethod= document.getElementById('bulkMethod');
  const restoreBtn= document.getElementById('bulkRestoreBtn');
  const deleteBtn = document.getElementById('bulkDeleteBtn');

  const rows = () => Array.from(document.querySelectorAll('.rowCheckbox'));
  const checked = () => rows().filter(x=>x.checked);

  function update() {
    const c = checked().length, t = rows().length;
    [restoreBtn, deleteBtn].forEach(b=> b.disabled = c===0);
    if (c===0) { selectAll.checked=false; selectAll.indeterminate=false; }
    else if (c===t) { selectAll.checked=true; selectAll.indeterminate=false; }
    else { selectAll.checked=false; selectAll.indeterminate=true; }
  }
  selectAll.addEventListener('change', e => { rows().forEach(cb=>cb.checked=e.target.checked); update(); });
  rows().forEach(cb => cb.addEventListener('change', update)); update();

  function submitBulk(btn){
    const ids = checked().map(x=>x.value);
    if (!ids.length) return;
    bulkForm.querySelectorAll('input[name="ids[]"]').forEach(el=>el.remove());
    ids.forEach(id=>{ const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; bulkForm.appendChild(i); });
    bulkForm.action = btn.dataset.action;
    bulkMethod.value= btn.dataset.method || 'POST';
    bulkForm.method = 'POST';
    if (btn===deleteBtn && !confirm('Xóa vĩnh viễn các mục đã chọn?')) return;
    bulkForm.submit();
  }
  restoreBtn.addEventListener('click', ()=> submitBulk(restoreBtn));
  deleteBtn.addEventListener('click', ()=> submitBulk(deleteBtn));
});
</script>
@endsection
