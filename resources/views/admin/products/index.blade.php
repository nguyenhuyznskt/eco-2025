@extends('admin.layouts.layout')

@section('content')
<div class="p-6 w-full mx-auto space-y-6">

  {{-- 🧩 Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <h1 class="text-3xl font-bold text-gray-800">📦 Sản phẩm</h1>

    <div class="flex gap-3">
      <a href="{{ route('admin.product.trashed') }}"
         class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
        🗑️ Thùng rác
      </a>
      <a href="{{ route('admin.product.create') }}"
         class="px-4 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
        ➕ Thêm mới
      </a>
    </div>
  </div>

  {{-- 🔍 Bộ lọc nâng cao --}}
  @php
    // Hàm đệ quy hiển thị danh mục cha–con
    function renderCategoryOptions($categories, $parentId = null, $prefix = '', $selected = null) {
        foreach ($categories->where('parent_id', $parentId) as $cat) {
            echo '<option value="'.$cat->id.'" '.($selected == $cat->id ? 'selected' : '').'>'.$prefix.$cat->name.'</option>';
            renderCategoryOptions($categories, $cat->id, $prefix.'— ', $selected);
        }
    }
  @endphp

  <form id="filterForm" method="GET"
        class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end bg-gray-50 p-4 rounded-xl border border-gray-200">

    {{-- Từ khóa --}}
    <div class="col-span-2">
      <label class="block text-sm font-medium text-gray-600">Từ khóa</label>
      <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm tên hoặc slug..."
             class="mt-1 w-full border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500 auto-submit">
    </div>

    {{-- Danh mục --}}
    <div>
      <label class="block text-sm font-medium text-gray-600">Danh mục</label>
      <select name="category_id"
              class="mt-1 w-full border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 auto-submit">
        <option value="">Tất cả</option>
        @php
          renderCategoryOptions($categories, null, '', request('category_id'));
        @endphp
      </select>
    </div>

    {{-- Vendor --}}
    <div>
      <label class="block text-sm font-medium text-gray-600">Nhà bán</label>
      <select name="vendor_id"
              class="mt-1 w-full border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 auto-submit">
        <option value="">Tất cả</option>
        @foreach($vendors as $v)
          <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
            {{ $v->shop_name }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Trạng thái --}}
    <div>
      <label class="block text-sm font-medium text-gray-600">Trạng thái</label>
      <select name="status"
              class="mt-1 w-full border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 auto-submit">
        <option value="">Tất cả</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hiển thị</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ẩn</option>
      </select>
    </div>

    {{-- Khoảng giá --}}
    <div>
      <label class="block text-sm font-medium text-gray-600">Khoảng giá</label>
      <div class="flex gap-2">
        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ"
               class="w-1/2 border-gray-300 rounded-md px-2 py-1 focus:ring-indigo-500 focus:border-indigo-500 auto-submit">
        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến"
               class="w-1/2 border-gray-300 rounded-md px-2 py-1 focus:ring-indigo-500 focus:border-indigo-500 auto-submit">
      </div>
    </div>

    {{-- Nổi bật --}}
    <div>
      <label class="block text-sm font-medium text-gray-600">Nổi bật</label>
      <select name="featured"
              class="mt-1 w-full border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 auto-submit">
        <option value="">Tất cả</option>
        <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Có</option>
        <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Không</option>
      </select>
    </div>

    {{-- Reset --}}
    <div class="col-span-full flex justify-end">
      <a href="{{ route('admin.product.index') }}"
         class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
         🔄 Reset
      </a>
    </div>
  </form>

  {{-- 📋 Danh sách --}}
  <form id="bulkForm" method="POST" class="space-y-4">
    @csrf
    <input type="hidden" name="_method" id="bulkMethod" value="POST">

    <div id="tableContainer" class="bg-white rounded-2xl shadow border border-gray-200 overflow-x-auto relative">
      {{-- 🔄 Loader --}}
      <div id="loadingOverlay" class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center">
        <div class="flex flex-col items-center">
          <div class="w-6 h-6 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-2"></div>
          <span class="text-gray-700 text-sm">Đang tải...</span>
        </div>
      </div>
    
      <table class="min-w-max w-full text-sm text-gray-700 whitespace-nowrap">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="px-4 py-3 text-center w-12">
              <input type="checkbox" id="selectAll" class="w-4 h-4">
            </th>
            <th class="px-4 py-3 text-center font-medium">Ảnh</th>
            <th class="px-4 py-3 text-left font-medium">Tên</th>
            <th class="px-4 py-3 text-left font-medium">Slug</th>
            <th class="px-4 py-3 text-center font-medium">Giá</th>
            <th class="px-4 py-3 text-center font-medium">Meta</th>
            <th class="px-4 py-3 text-center font-medium">Biến thể</th>
            <th class="px-4 py-3 text-center font-medium">Hành động</th>
          </tr>
        </thead>
    
        <tbody>
          @forelse($data as $row)
          <tr class="border-t hover:bg-gray-50 transition">
            <td class="px-4 py-3 text-center">
              <input type="checkbox" class="rowCheckbox w-4 h-4" value="{{ $row->id }}">
            </td>
    
            {{-- 🖼️ Ảnh --}}
            <td class="px-4 py-3 text-center">
              @php
                $img = $row->image ?? optional($row->images->first())->path ?? null;
              @endphp
              @if($img)
                <img src="{{ asset('storage/'.$img) }}" 
                     alt="{{ $row->name }}" 
                     class="w-12 h-12 object-cover rounded-md shadow-sm border mx-auto hover:scale-110 transition-transform duration-200">
              @else
                <div class="w-12 h-12 bg-gray-100 border rounded-md flex items-center justify-center text-gray-400 text-xs mx-auto">
                  N/A
                </div>
              @endif
            </td>
    
            <td class="px-4 py-3 font-medium text-gray-800">{{ $row->name }}</td>
            <td class="px-4 py-3 text-gray-600">{{ $row->slug }}</td>
            <td class="px-4 py-3 text-center">{{ number_format($row->price, 0, ',', '.') }} VND</td>
    
            {{-- Meta --}}
            <td class="px-4 py-3 text-left align-top max-w-[240px]">
              @if(!empty($row->meta))
                <div class="text-xs text-gray-500 leading-5 overflow-hidden text-ellipsis line-clamp-2"
                     style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical;">
                  @foreach(array_slice($row->meta, 0, 2) as $k => $v)
                    <span title="{{ $k }}: {{ $v }}">
                      <strong>{{ $k }}:</strong> {{ Str::limit($v, 40) }}
                    </span>
                    @if(!$loop->last), @endif
                  @endforeach
                </div>
              @else
                <span class="text-gray-400 italic">—</span>
              @endif
            </td>
    
            <td class="px-4 py-3 text-center">{{ $row->variants_count }}</td>
    
            {{-- Hành động --}}
            <td class="px-4 py-3 text-right whitespace-nowrap min-w-[120px]">
              <div class="flex justify-end gap-2">
                <a href="{{ route('admin.product.edit', $row->id) }}"
                   class="px-3 py-1.5 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                  ✏️ Sửa
                </a>
                <form action="{{ route('admin.product.destroy', $row->id) }}" method="POST"
                      onsubmit="return confirm('Xóa mềm sản phẩm này?')">
                  @csrf @method('DELETE')
                  <button class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    🗑️ Xóa
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
              😕 Chưa có sản phẩm nào
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    

    {{-- ⚙️ Bulk actions & Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div class="flex gap-2">
        <button type="button" id="bulkDeleteBtn"
                data-action="{{ route('admin.product.bulk-delete') }}"
                data-method="POST"
                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition disabled:opacity-50"
                disabled>
          Xóa mềm đã chọn
        </button>
      </div>
      <div>{{ $data->links() }}</div>
    </div>
  </form>
</div>

{{-- 💡 Script --}}
<script>
document.addEventListener('DOMContentLoaded', ()=>{
  // --- auto filter ---
  const form = document.getElementById('filterForm');
  const loader = document.getElementById('loadingOverlay');
  let timer;

  const submitForm = () => {
    loader.classList.remove('hidden');
    form.submit();
  };

  form.querySelectorAll('.auto-submit').forEach(el => {
    if (el.tagName === 'SELECT' || el.type === 'number') {
      el.addEventListener('change', submitForm);
    }
    if (el.type === 'text') {
      el.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(submitForm, 500);
      });
    }
  });

  // --- bulk delete ---
  const selectAll = document.getElementById('selectAll');
  const bulkForm = document.getElementById('bulkForm');
  const bulkMethod = document.getElementById('bulkMethod');
  const delBtn = document.getElementById('bulkDeleteBtn');
  const rows = () => Array.from(document.querySelectorAll('.rowCheckbox'));
  const checked = () => rows().filter(x=>x.checked);

  function update() {
    const c = checked().length, t = rows().length;
    delBtn.disabled = c === 0;
    if (c === 0) { selectAll.checked = false; selectAll.indeterminate = false; }
    else if (c === t) { selectAll.checked = true; selectAll.indeterminate = false; }
    else { selectAll.checked = false; selectAll.indeterminate = true; }
  }

  selectAll.addEventListener('change', e => { rows().forEach(cb => cb.checked = e.target.checked); update(); });
  rows().forEach(cb => cb.addEventListener('change', update));
  update();

  delBtn.addEventListener('click', ()=>{
    const ids = checked().map(x => x.value);
    if (!ids.length) return;
    bulkForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    ids.forEach(id => {
      const i = document.createElement('input');
      i.type = 'hidden';
      i.name = 'ids[]';
      i.value = id;
      bulkForm.appendChild(i);
    });
    bulkForm.action = delBtn.dataset.action;
    bulkMethod.value = delBtn.dataset.method || 'POST';
    bulkForm.method = 'POST';
    bulkForm.submit();
  });
});
</script>
@endsection
