@extends('admin.layouts.layout')

@section('content')
<div class="p-6 max-w-6xl mx-auto space-y-8">

  {{-- 🧱 Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        🛍️ Tạo sản phẩm kèm biến thể
      </h1>
      <p class="text-gray-500 text-sm mt-1">Thêm mới sản phẩm cơ bản, đồng thời tạo các biến thể tùy theo thuộc tính.</p>
    </div>

    <a href="{{ route('admin.product.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
      <i class="bx bx-arrow-back text-lg"></i>
      <span>Quay lại danh sách</span>
    </a>
  </div>

  {{-- 🧾 FORM --}}
  <form action="{{ route('admin.product.store') }}" method="POST"
        class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 space-y-8">
    @csrf

    {{-- 🧩 Thông tin cơ bản --}}
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="bx bx-info-circle text-indigo-500 text-xl"></i> Thông tin sản phẩm
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Slug (tùy chọn)</label>
          <input type="text" name="slug" value="{{ old('slug') }}"
                 class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Giá bán</label>
          <input type="number" name="price" value="{{ old('price') }}" step="0.01"
                 class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
          <input type="number" name="compare_price" value="{{ old('compare_price') }}" step="0.01"
                 class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>
    </div>

    {{-- 🗂️ Danh mục & vendor --}}
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="bx bx-category text-indigo-500 text-xl"></i> Danh mục & Nhà bán
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Danh mục</label>
          <select name="category_id"
                  class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $cat)
  <option value="{{ $cat->id }}">
    {{ $cat->parent ? $cat->parent->name.' → '.$cat->name : $cat->name }}
  </option>
@endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Nhà bán</label>
          <select name="vendor_id"
                  class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">-- Chọn vendor --</option>
            @foreach($vendors as $v)
              <option value="{{ $v->id }}">{{ $v->shop_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- ⚙️ Thuộc tính biến thể --}}
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="bx bx-layer text-indigo-500 text-xl"></i> Thuộc tính biến thể
      </h2>

      <div class="space-y-4">
        @foreach($variationAttributes as $attr)
          <div class="border border-gray-200 rounded-lg p-4">
            <div class="font-medium text-gray-800 mb-2">{{ $attr->name }}</div>
            <div class="flex flex-wrap gap-2">
              @foreach($attr->values as $val)
                <label class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 border rounded-lg hover:bg-gray-100 transition">
                  <input type="checkbox"
                         name="attribute_values[{{ $attr->id }}][]"
                         value="{{ $val->id }}"
                         data-attr-name="{{ $attr->name }}"
                         data-val-label="{{ $val->label ?? $val->value }}"
                         class="variant-value w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300">
                  <span class="text-gray-700">{{ $val->label ?? $val->value }}</span>
                </label>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- 🔨 Button tạo biến thể --}}
    <button type="button" id="btnGenerate"
            class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
      <i class="bx bx-magic-wand text-lg"></i> Tạo biến thể
    </button>

    {{-- 🧮 Bảng biến thể --}}
    <div id="variantTable" class="hidden">
      <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-3 flex items-center gap-2">
        <i class="bx bx-grid-alt text-indigo-500 text-xl"></i> Danh sách biến thể
      </h2>

      <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-4 py-2 font-medium">Tổ hợp</th>
              <th class="px-4 py-2 font-medium">SKU</th>
              <th class="px-4 py-2 font-medium">Giá</th>
              <th class="px-4 py-2 font-medium">Giá gốc</th>
              <th class="px-4 py-2 font-medium text-center">Hiển thị</th>
              <th class="px-4 py-2 font-medium">Ảnh</th>

              <th class="px-4 py-2"></th>
              
            </tr>
          </thead>
          <tbody id="variantBody"></tbody>
        </table>
      </div>
    </div>
    @include('admin.products._spec', ['product' => $product ?? null])


    {{-- 🖊️ Submit --}}
    <div class="flex justify-end pt-4">
      <button type="submit"
              class="inline-flex items-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
        <i class="bx bx-save text-lg"></i> Lưu sản phẩm
      </button>
    </div>
  </form>
</div>

{{-- 🧠 Script sinh tổ hợp --}}
<script>
(function(){
  const btn = document.getElementById('btnGenerate');
  const table = document.getElementById('variantTable');
  const tbody = document.getElementById('variantBody');

  const cartesian = (arrays) => arrays.reduce((a, b) => a.flatMap(d => b.map(e => [].concat(d, e))), [[]]);

  btn.addEventListener('click', () => {
    const groups = [];
    document.querySelectorAll('.variant-value:checked').forEach(cb => {
      const attr = cb.dataset.attrName;
      const val  = cb.dataset.valLabel;
      const id   = cb.value;
      let g = groups.find(x => x.attr === attr);
      if (!g) { g = { attr, items: [] }; groups.push(g); }
      g.items.push({ id, val });
    });

    if (groups.length < 1) {
      alert('⚠️ Chọn ít nhất 1 thuộc tính!');
      return;
    }

    const combos = cartesian(groups.map(g => g.items));
    tbody.innerHTML = '';
    combos.forEach((combo, i) => {
      const labels = combo.map(c => c.val).join(' / ');
      const ids    = combo.map(c => c.id);
      const tr = document.createElement('tr');
      tr.className = 'border-t hover:bg-gray-50';
      tr.innerHTML = `
  <td class="px-4 py-2 font-medium text-gray-800">${labels}
    ${ids.map(id => `<input type="hidden" name="combinations[${i}][value_ids][]" value="${id}">`).join('')}
  </td>
  <td class="px-4 py-2"><input name="combinations[${i}][sku]" class="border-gray-300 rounded-lg w-32"></td>
  <td class="px-4 py-2"><input type="number" step="0.01" name="combinations[${i}][price]" class="border-gray-300 rounded-lg w-24"></td>
  <td class="px-4 py-2"><input type="number" step="0.01" name="combinations[${i}][compare_price]" class="border-gray-300 rounded-lg w-24"></td>
  <td class="px-4 py-2 text-center"><input type="checkbox" name="combinations[${i}][is_active]" value="1" checked></td>

  <!-- 🖼️ Cột upload ảnh -->
  <td class="px-4 py-2">
    <input type="file" name="combinations[${i}][image]" accept="image/*" class="text-sm">
  </td>

  <td class="px-4 py-2 text-right">
    <button type="button" class="text-red-600 hover:text-red-800 remove"><i class="bx bx-trash"></i></button>
  </td>`;
      tbody.appendChild(tr);
    });

    table.classList.remove('hidden');

    tbody.querySelectorAll('.remove').forEach(btn =>
      btn.addEventListener('click', e => {
        e.target.closest('tr').remove();
        if (!tbody.children.length) table.classList.add('hidden');
      })
    );
  });
})();
</script>
@endsection
