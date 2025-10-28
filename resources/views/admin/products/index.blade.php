@extends('admin.layouts.layout')
@section('content')
<div class="p-6 space-y-4">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Sản phẩm</h1>
    <div class="flex gap-2">
      <a href="{{ route('admin.product.trashed') }}" class="px-3 py-2 bg-gray-200 rounded-lg">Thùng rác</a>
      <a href="{{ route('admin.product.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg">Thêm mới</a>
    </div>
  </div>

  <form class="flex gap-2" method="GET">
    <input name="keyword" value="{{ request('keyword') }}" placeholder="Tìm tên/slug..."
           class="border-gray-300 rounded-lg w-64">
    <button class="px-3 py-2 bg-gray-100 rounded-lg">Lọc</button>
  </form>

  <form id="bulkForm" method="POST" class="space-y-2">
    @csrf
    <input type="hidden" name="_method" id="bulkMethod" value="POST">

    <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="px-4 py-3"><input type="checkbox" id="selectAll" class="w-4 h-4"></th>
            <th class="px-4 py-3 text-left">Tên</th>
            <th class="px-4 py-3">Slug</th>
            <th class="px-4 py-3">Giá</th>
            <th class="px-4 py-3">Biến thể</th>
            <th class="px-4 py-3 text-right">Hành động</th>
          </tr>
        </thead>
        <tbody>
        @forelse($data as $row)
          <tr class="border-t">
            <td class="px-4 py-3">
              <input type="checkbox" class="rowCheckbox w-4 h-4" value="{{ $row->id }}">
            </td>
            <td class="px-4 py-3 font-medium text-gray-800">{{ $row->name }}</td>
            <td class="px-4 py-3 text-center">{{ $row->slug }}</td>
            <td class="px-4 py-3 text-center">{{ number_format($row->price,0,',','.') }}</td>
            <td class="px-4 py-3 text-center">{{ $row->variants_count }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <a class="px-2 py-1 bg-gray-100 rounded-lg" href="{{ route('admin.product.edit',$row->id) }}">Sửa</a>
                <form action="{{ route('admin.product.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Xóa mềm sản phẩm này?')">
                  @csrf @method('DELETE')
                  <button class="px-2 py-1 bg-red-600 text-white rounded-lg">Xóa</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Chưa có sản phẩm</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="flex items-center justify-between">
      <div class="flex gap-2">
        <button type="button" id="bulkDeleteBtn"
                data-action="{{ route('admin.product.bulk-delete') }}"
                data-method="POST"
                class="px-3 py-2 bg-red-600 text-white rounded-lg disabled:opacity-50"
                disabled>Xóa mềm đã chọn</button>
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
  const delBtn    = document.getElementById('bulkDeleteBtn');

  const rows = () => Array.from(document.querySelectorAll('.rowCheckbox'));
  const checked = () => rows().filter(x=>x.checked);

  function update() {
    const c = checked().length, t = rows().length;
    delBtn.disabled = c===0;
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
    ids.forEach(id=>{
      const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=id; bulkForm.appendChild(i);
    });
    bulkForm.action = btn.dataset.action;
    bulkMethod.value = btn.dataset.method || 'POST';
    bulkForm.method = 'POST';
    bulkForm.submit();
  }

  delBtn.addEventListener('click', ()=> submitBulk(delBtn));
});
</script>
@endsection
