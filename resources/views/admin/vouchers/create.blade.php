@extends('admin.layouts.layout')
@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-8">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">➕ Thêm Voucher mới</h1>
    <a href="{{ route('admin.voucher.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">← Quay lại danh sách</a>
  </div>

  <form action="{{ route('admin.voucher.store') }}" method="POST" class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm space-y-6">
    @csrf

    {{-- THÔNG TIN CƠ BẢN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Mã voucher *</label>
        <input type="text" name="code" value="{{ old('code') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Loại giảm giá *</label>
        <select name="type" id="voucherType"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
          <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Cố định (₫)</option>
          <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giá trị *</label>
        <input type="number" step="0.01" name="value" value="{{ old('value') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      {{-- Giá trị tối đa (ẩn khi chọn cố định) --}}
      <div id="maxValueGroup">
        <label class="block text-sm font-medium text-gray-700 mb-1">Giá trị tối đa</label>
        <input type="number" step="0.01" name="max_value" id="max_value" value="{{ old('max_value') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Đơn hàng tối thiểu</label>
        <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>

      <div class="flex items-center gap-2 mt-7">
        {{-- hidden để luôn gửi is_active=0 khi checkbox bỏ tích --}}
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-gray-400">
        <label class="text-sm text-gray-700">Kích hoạt</label>
      </div>
      

    {{-- GIỚI HẠN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giới hạn toàn hệ thống</label>
        <input type="number" name="usage_limit_global" value="{{ old('usage_limit_global') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Giới hạn mỗi người dùng</label>
        <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
    </div>

    {{-- THỜI GIAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày bắt đầu</label>
        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày kết thúc</label>
        <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none">
      </div>
    </div>

    {{-- META --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Thông tin thêm (meta JSON)</label>
      <textarea name="meta" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 focus:border-gray-500 outline-none"
                placeholder='{"note": "Chỉ áp dụng cho thành viên VIP"}'>{{ old('meta') }}</textarea>
    </div>

    {{-- BUTTON --}}
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
      <a href="{{ route('admin.voucher.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm text-gray-700 rounded-lg">Hủy</a>
      <button class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm rounded-lg">Lưu</button>
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
