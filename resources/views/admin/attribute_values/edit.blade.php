@extends('admin.layouts.layout')

@section('content')
<div class="max-w-3xl mx-auto p-8">
  {{-- Header --}}
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">🛠️ Chỉnh sửa giá trị thuộc tính</h1>
      <p class="text-gray-500 mt-1 text-sm">Cập nhật thông tin cho giá trị con của thuộc tính sản phẩm.</p>
    </div>

    <a href="{{ route('admin.attribute_value.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300 transition-all duration-200">
      <i class="bx bx-arrow-back text-lg"></i>
      <span>Quay lại</span>
    </a>
  </div>

  {{-- Form --}}
  <form action="{{ route('admin.attribute_value.update', $row) }}" method="POST"
        class="bg-white rounded-2xl shadow-md border border-gray-200 p-8 space-y-6">
    @csrf
    @method('PUT')

    {{-- Thuộc tính cha --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Thuộc tính <span class="text-red-500">*</span></label>
      <div class="relative">
        <i class="bx bx-layer absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <select name="attribute_id"
                class="w-full h-11 pl-9 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                required>
          <option value="">-- Chọn thuộc tính --</option>
          @foreach($attributes as $id => $name)
            <option value="{{ $id }}" {{ old('attribute_id', $row->attribute_id) == $id ? 'selected' : '' }}>
              {{ $name }}
            </option>
          @endforeach
        </select>
      </div>
      @error('attribute_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Giá trị (value) --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Giá trị (value) <span class="text-red-500">*</span></label>
      <div class="relative">
        <i class="bx bx-code-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="text" name="value" value="{{ old('value', $row->value) }}"
               class="w-full h-11 pl-9 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
               required>
      </div>
      @error('value')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Nhãn hiển thị --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Nhãn hiển thị (label)</label>
      <div class="relative">
        <i class="bx bx-purchase-tag-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="text" name="label" value="{{ old('label', $row->label) }}"
               class="w-full h-11 pl-9 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
               placeholder="Ví dụ: Đỏ, Cỡ lớn...">
      </div>
      @error('label')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Thứ tự hiển thị --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Thứ tự hiển thị</label>
      <div class="relative">
        <i class="bx bx-sort-alt-2 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="number" name="sort_order" value="{{ old('sort_order', $row->sort_order ?? 0) }}" min="0"
               class="w-full h-11 pl-9 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
      </div>
      @error('sort_order')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Nút hành động --}}
    <div class="flex justify-end pt-4 border-t border-gray-100">
      <a href="{{ route('admin.attribute_value.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300 transition-all duration-150">
         <i class="bx bx-x"></i> Huỷ
      </a>

      <button type="submit"
              class="ml-3 inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium rounded-lg shadow hover:shadow-md hover:scale-[1.02] transition-all duration-200">
        <i class="bx bx-save text-lg"></i>
        <span>Cập nhật</span>
      </button>
    </div>
  </form>
</div>

{{-- Script hiệu ứng focus --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(el => {
      el.addEventListener('focus', () => el.classList.add('ring-2', 'ring-indigo-400', 'bg-white'));
      el.addEventListener('blur', () => el.classList.remove('ring-2', 'ring-indigo-400', 'bg-white'));
    });
  });
</script>
@endsection
