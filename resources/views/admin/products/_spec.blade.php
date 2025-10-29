<div class="space-y-3" x-data="specRepeater()">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold text-gray-800">Thông tin kỹ thuật</h2>
      <button type="button" @click="add()"
        class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        + Thêm dòng
      </button>
    </div>
  
    <div class="border rounded-lg divide-y">
      <template x-for="(row, idx) in rows" :key="idx">
        <div class="grid grid-cols-12 gap-3 p-3">
          <div class="col-span-5">
            <input type="text" class="w-full rounded-md border-gray-300"
                   :name="`spec_key[${idx}]`" x-model="row.k" placeholder="Thuộc tính (VD: Màn hình)">
          </div>
          <div class="col-span-6">
            <input type="text" class="w-full rounded-md border-gray-300"
                   :name="`spec_value[${idx}]`" x-model="row.v" placeholder="Giá trị (VD: 6.7” Super Retina XDR)">
          </div>
          <div class="col-span-1 text-right">
            <button type="button" class="text-red-600" @click="remove(idx)">Xóa</button>
          </div>
        </div>
      </template>
  
      <template x-if="rows.length === 0">
        <div class="p-3 text-sm text-gray-500">Chưa có thông số — bấm “Thêm dòng”.</template>
    </div>
  </div>
  
  @php
  $initialSpecs = [];
  if (isset($product) && is_array($product->meta)) {
      foreach ($product->meta as $k => $v) {
          $initialSpecs[] = ['k' => $k, 'v' => $v];
      }
  }
@endphp

<script>
function specRepeater() {
  const initial = @json($initialSpecs);
  return {
    rows: initial.length ? initial : [],
    add() { this.rows.push({ k: '', v: '' }); },
    remove(i) { this.rows.splice(i, 1); },
  }
}
</script>
  