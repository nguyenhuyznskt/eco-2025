@extends('admin.layouts.layout')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-8">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">✏️ Sửa Voucher: {{ $voucher->code }}</h1>
    <a href="{{ route('admin.voucher.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">← Quay lại danh sách</a>
  </div>

  <form action="{{ route('admin.voucher.update', $voucher) }}" method="POST" class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm space-y-6">
    @csrf
    @method('PUT')

    {{-- THÔNG TIN CƠ BẢN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- Mã voucher --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Mã voucher *</label>
        <input type="text" name="code" value="{{ old('code', $voucher->code) }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      {{-- Loại giảm giá --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Loại giảm giá *</label>
        <select name="type" id="voucherType"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
          <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Cố định (₫)</option>
          <option value="percent" {{ old('type', $voucher->type) === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
        </select>
      </div>

      {{-- Giá trị --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giá trị *</label>
        <input type="number" step="0.01" name="value" value="{{ old('value', $voucher->value) }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      {{-- Giá trị tối đa (ẩn khi chọn cố định) --}}
      <div id="maxValueGroup">
        <label class="block text-sm font-medium text-gray-700 mb-1">Giá trị tối đa</label>
        <input type="number" step="0.01" name="max_value" id="max_value"
               value="{{ old('max_value', $voucher->max_value) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      {{-- Đơn hàng tối thiểu --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Đơn hàng tối thiểu</label>
        <input type="number" step="0.01" name="min_order_amount"
               value="{{ old('min_order_amount', $voucher->min_order_amount) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      {{-- Trạng thái hoạt động --}}
      <div class="flex items-center gap-2 mt-7">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-gray-400">
        <label class="text-sm text-gray-700">Kích hoạt</label>
      </div>
    </div>

    {{-- GIỚI HẠN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giới hạn toàn hệ thống</label>
        <input type="number" name="usage_limit_global"
               value="{{ old('usage_limit_global', $voucher->usage_limit_global) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giới hạn mỗi người dùng</label>
        <input type="number" name="usage_limit_per_user"
               value="{{ old('usage_limit_per_user', $voucher->usage_limit_per_user) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Đã sử dụng</label>
        <input type="number" name="used_count" value="{{ $voucher->used_count }}"
               readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed">
      </div>
    </div>

    {{-- THỜI GIAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày bắt đầu</label>
        <input type="datetime-local" name="start_at"
               value="{{ old('start_at', $voucher->start_at ? $voucher->start_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày kết thúc</label>
        <input type="datetime-local" name="end_at"
               value="{{ old('end_at', $voucher->end_at ? $voucher->end_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
    </div>

    {{-- META --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Thông tin thêm (meta JSON)</label>
      <textarea name="meta" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none"
                placeholder='{"note": "Chỉ áp dụng cho thành viên VIP"}'>{{ old('meta', is_array($voucher->meta) ? json_encode($voucher->meta) : $voucher->meta) }}</textarea>
    </div>

    {{-- BUTTON --}}
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
      <a href="{{ route('admin.voucher.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm text-gray-700 rounded-lg">Hủy</a>
      <button class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm rounded-lg">Cập nhật</button>
    </div>
  </form>
</div>

{{-- JS ẩn/hiện giá trị tối đa --}}
<script>
  const typeSelect = document.getElementById('voucherType');
  const maxValueGroup = document.getElementById('maxValueGroup');
  const maxValueInput = document.getElementById('max_value');

  function toggleMaxValue() {
    const isPercent = typeSelect.value === 'percent';
    if (isPercent) {
      maxValueGroup.classList.remove('hidden');
      maxValueInput.disabled = false;
    } else {
      maxValueGroup.classList.add('hidden');
      maxValueInput.value = '';
      maxValueInput.disabled = true;
    }
  }

  typeSelect.addEventListener('change', toggleMaxValue);
  toggleMaxValue();
</script>
@endsection
