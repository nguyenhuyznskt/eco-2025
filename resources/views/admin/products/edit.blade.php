@extends('admin.layouts.layout')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">
  <h1 class="text-3xl font-bold text-gray-800">✏️ Sửa sản phẩm</h1>

  <form action="{{ route('updateProduct', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-xl shadow">
    @csrf
    @method('PUT')

    {{-- 🔹 Thông tin cơ bản --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Giá bán</label>
        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Giá gốc</label>
        <input type="number" name="compare_price" step="0.01" value="{{ old('compare_price', $product->compare_price) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
    </div>

    {{-- 🔹 Danh mục & vendor --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">Danh mục</label>
        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
          <option value="">-- Chọn danh mục --</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
            {{ $cat->parent ? $cat->parent->name . ' → ' . $cat->name : $cat->name }}
          </option>
        @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Nhà bán</label>
        <select name="vendor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
          <option value="">-- Chọn nhà bán --</option>
          @foreach($vendors as $v)
            <option value="{{ $v->id }}" {{ old('vendor_id', $product->vendor_id) == $v->id ? 'selected' : '' }}>
              {{ $v->shop_name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    {{-- 🔹 Mô tả --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
      <textarea name="short_description" rows="2"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('short_description', $product->short_description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
      <textarea name="description" rows="5"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $product->description) }}</textarea>
    </div>

    {{-- 🔹 Ảnh sản phẩm --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Ảnh sản phẩm</label>
      <input type="file" name="image" accept="image/*"
             class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:ring-indigo-500 focus:border-indigo-500">
      @if($product->image)
        <div class="mt-3">
          <p class="text-sm text-gray-600 mb-1">Ảnh hiện tại:</p>
          <img src="{{ asset('storage/'.$product->image) }}" alt="Product image" class="w-28 h-28 object-cover border rounded-md shadow-sm">
        </div>
      @endif
    </div>

    {{-- 🔹 Trạng thái --}}
    <div class="flex gap-6">
      <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded text-indigo-600">
        <span>Hiển thị</span>
      </label>
      <label class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded text-indigo-600">
        <span>Nổi bật</span>
      </label>
    </div>

    {{-- 🔹 Thuộc tính biến thể --}}
    <div class="space-y-4">
      <h2 class="text-xl font-semibold text-gray-800">Thuộc tính biến thể</h2>
      @foreach($variationAttributes as $attr)
        <div class="border rounded-lg p-4">
          <div class="font-medium text-gray-800 mb-2">{{ $attr->name }}</div>
          <div class="flex flex-wrap gap-3">
            @foreach($attr->values as $val)
              <label class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border">
                <input type="checkbox"
                       name="attribute_values[{{ $attr->id }}][]"
                       value="{{ $val->id }}"
                       data-attr-name="{{ $attr->name }}"
                       data-val-label="{{ $val->label ?? $val->value }}"
                       class="variant-value"
                       {{ in_array($val->id, $selectedMap[$attr->id] ?? []) ? 'checked' : '' }}>
                <span>{{ $val->label ?? $val->value }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>

    {{-- 🔹 Nút tạo biến thể --}}
    <div>
      <button type="button" id="btnGenerate"
              class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Tạo biến thể mới</button>
    </div>

    {{-- 🔹 Checkbox thay thế toàn bộ --}}
    <div class="mt-4 border-t pt-4">
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" name="replace_variants" value="1" class="rounded text-indigo-600">
        <span class="text-sm text-gray-700">Thay thế toàn bộ biến thể cũ bằng tổ hợp mới</span>
      </label>
    </div>

    {{-- 🔹 Bảng biến thể --}}
   {{-- 🔹 Bảng biến thể --}}
<div id="variantTable" class="{{ count($variants) ? '' : 'hidden' }}">
  <h2 class="text-xl font-semibold text-gray-800 mb-2">Biến thể</h2>

  <div class="overflow-x-auto border rounded-lg">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 text-left">Tổ hợp</th>
          <th class="px-4 py-2">SKU</th>
          <th class="px-4 py-2">Giá</th>
          <th class="px-4 py-2">Giá gốc</th>
          <th class="px-4 py-2 text-center">Hiển thị</th>
          <th class="px-4 py-2 text-center">Ảnh</th>
          <th class="px-4 py-2 text-right">#</th>
        </tr>
      </thead>

      <tbody id="variantBody">
        {{-- 🧩 Hiển thị biến thể cũ --}}
        @foreach($variants as $i => $variant)
          @php
            $valueIds = $variant->attributes['value_ids'] ?? [];
            $labels = DB::table('attribute_values')
                        ->whereIn('id', $valueIds)
                        ->pluck('value')
                        ->implode(' / ');
          @endphp
          <tr class="border-t hover:bg-gray-50">
            <td class="px-4 py-2">
              {{ $labels }}
              @foreach($valueIds as $vid)
                <input type="hidden" name="combinations[{{ $i }}][value_ids][]" value="{{ $vid }}">
              @endforeach
            </td>
            <td class="px-4 py-2">
              <input name="combinations[{{ $i }}][sku]" value="{{ $variant->sku }}"
                     class="border-gray-300 rounded-lg w-32">
            </td>
            <td class="px-4 py-2">
              <input type="number" step="0.01" name="combinations[{{ $i }}][price]"
                     value="{{ $variant->price }}" class="border-gray-300 rounded-lg w-24">
            </td>
            <td class="px-4 py-2">
              <input type="number" step="0.01" name="combinations[{{ $i }}][compare_price]"
                     value="{{ $variant->compare_price }}" class="border-gray-300 rounded-lg w-24">
            </td>
            <td class="px-4 py-2 text-center">
              <input type="checkbox" name="combinations[{{ $i }}][is_active]" value="1"
                     {{ $variant->is_active ? 'checked' : '' }}>
            </td>
            <td class="px-4 py-2 text-center">
              @php
                  $img = optional($variant->images->first())->path ?? null;
              @endphp
            
              {{-- Ảnh cũ --}}
              @if($img)
                <div class="relative group inline-block">
                  <img src="{{ asset('storage/'.$img) }}" alt="variant image"
                       class="w-12 h-12 object-cover rounded-md border shadow-sm mx-auto group-hover:opacity-75 transition">
                  <div class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition">
                    Ảnh cũ
                  </div>
                </div>
              @else
                <div class="w-12 h-12 bg-gray-100 border rounded-md flex items-center justify-center text-gray-400 text-xs mx-auto">
                  N/A
                </div>
              @endif
            
              {{-- Ảnh mới --}}
              <div class="mt-2 text-xs">
                <label class="block text-gray-600 mb-1">Ảnh mới:</label>
                <input type="file" name="combinations[{{ $i }}][image]" accept="image/*"
                       class="text-xs file:mr-2 file:px-2 file:py-1 file:rounded file:border-0 file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
              </div>
            </td>
            
            <td class="px-4 py-2 text-right">
              <button type="button" class="text-red-600 remove">Xóa</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>


    @include('admin.products._spec', ['product' => $product ?? null])

    {{-- 🔹 Nút cập nhật --}}
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
      <a href="{{ route('admin.product.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Quay lại</a>
      <button type="submit"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Cập nhật sản phẩm</button>
    </div>
  </form>
</div>

{{-- 🎬 JS sinh tổ hợp biến thể --}}
<script>
  (function(){
    const btn = document.getElementById('btnGenerate');
    const table = document.getElementById('variantTable');
    const tbody = document.getElementById('variantBody');
    let currentIndex = {{ count($variants) }}; // Đếm để không bị trùng index
  
    const cartesian = (arrays) => arrays.reduce(
      (a, b) => a.flatMap(d => b.map(e => [].concat(d, e))),
      [[]]
    );
  
    btn.addEventListener('click', () => {
      const groups = [];
      document.querySelectorAll('.variant-value:checked').forEach(cb => {
        const attr = cb.dataset.attrName;
        const val  = cb.dataset.valLabel;
        const id   = cb.value;
        let g = groups.find(x=>x.attr===attr);
        if(!g){ g = {attr, items:[]}; groups.push(g); }
        g.items.push({id, val});
      });
  
      if(groups.length < 1){ alert('⚠️ Chọn ít nhất 1 thuộc tính!'); return; }
  
      const combos = cartesian(groups.map(g=>g.items));
  
      combos.forEach((combo)=>{
        const i = currentIndex++;
        const labels = combo.map(c=>c.val).join(' / ');
        const ids = combo.map(c=>c.id);
        const tr = document.createElement('tr');
        tr.className='border-t';
        tr.innerHTML = `
          <td class="px-4 py-2">${labels}
            ${ids.map(id=>`<input type="hidden" name="combinations[${i}][value_ids][]" value="${id}">`).join('')}
          </td>
          <td class="px-4 py-2"><input name="combinations[${i}][sku]" class="border-gray-300 rounded-lg w-32" placeholder="SKU"></td>
          <td class="px-4 py-2"><input type="number" step="0.01" name="combinations[${i}][price]" class="border-gray-300 rounded-lg w-24"></td>
          <td class="px-4 py-2"><input type="number" step="0.01" name="combinations[${i}][compare_price]" class="border-gray-300 rounded-lg w-24"></td>
          <td class="px-4 py-2 text-center"><input type="checkbox" name="combinations[${i}][is_active]" value="1" checked></td>
          <td class="px-4 py-2 text-center"><input type="file" name="combinations[${i}][image]" class="text-xs"></td>
          <td class="px-4 py-2 text-right"><button type="button" class="text-red-600 remove">Xóa</button></td>
        `;
        tbody.appendChild(tr);
      });
  
      table.classList.remove('hidden');
  
      tbody.querySelectorAll('.remove').forEach(btn =>
        btn.addEventListener('click', e => {
          e.target.closest('tr').remove();
          if(!tbody.children.length) table.classList.add('hidden');
        })
      );
    });
  })();
  </script>
  
@endsection
