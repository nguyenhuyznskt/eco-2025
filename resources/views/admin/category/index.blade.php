@extends('admin.layouts.layout')

@section('content')

{{-- Flash message --}}
@if (session('success') || session('error'))
  <div id="flash-message" 
       class="mx-auto max-w-4xl my-4 px-4 py-3 rounded-xl shadow text-sm text-white font-medium {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}">
    {{ session('success') ?? session('error') }}
  </div>
@endif

<div class="p-6">
  {{-- Header --}}
  <div class="flex flex-wrap justify-between items-center mb-8">
    <h1 class="text-3xl font-semibold text-gray-800 flex items-center gap-2">
      📁 <span>Danh mục sản phẩm</span>
    </h1>
    <a href="{{ route('createCategory') }}" 
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
      <i class="bx bx-plus text-xl"></i> <span>Thêm danh mục cha</span>
    </a>
  </div>

  {{-- Danh sách --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($categories as $category)
      <div class="group relative bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500 opacity-0 group-hover:opacity-100 transition"></div>

        <div class="p-5 space-y-4">
          {{-- Tên danh mục --}}
          <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
              <i class="bx bxs-folder text-yellow-400 text-xl"></i>
              {{ $category->name }}
            </h2>
            <button data-collapse="#cat-{{ $category->id }}"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition">
              <i class="bx bx-chevron-down text-gray-600 text-xl transition-transform duration-300"></i>
            </button>
          </div>

          {{-- Danh mục con --}}
          <div id="cat-{{ $category->id }}" class="hidden mt-3 space-y-2">
            @if($category->children && $category->children->count() > 0)
              <div class="pl-3 border-l border-gray-200">
                @foreach($category->children as $child)
                  <div class="flex justify-between items-center bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-lg text-sm">
                    <span class="flex items-center gap-2">
                      <i class="bx bx-folder text-gray-500"></i> {{ $child->name }}
                    </span>
                    <div class="flex gap-1">
                      <a href="{{ route('categoryShow', $child->id) }}" class="text-blue-500 hover:text-blue-700"><i class="bx bxs-show"></i></a>
                      <a href="{{ route('categoryEdit', $child->id) }}" class="text-yellow-500 hover:text-yellow-600"><i class="bx bxs-edit"></i></a>
                      <form action="{{ route('categoryDestroy', $child->id) }}" method="POST" onsubmit="return confirm('Xoá {{ $child->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600"><i class="bx bxs-trash"></i></button>
                      </form>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-gray-400 italic text-sm">Không có danh mục con</p>
            @endif
          </div>

          {{-- Footer --}}
          <div class="pt-4 mt-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('createChildCategory', $category->id) }}" 
               class="px-3 py-1.5 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition">
              <i class="bx bx-plus"></i> Thêm con
            </a>
            <div class="flex gap-2">
              <a href="{{ route('categoryEdit', $category->id) }}" 
                 class="px-3 py-1.5 bg-yellow-400 text-white text-sm rounded-lg hover:bg-yellow-500 transition">
                <i class="bx bxs-edit"></i> Sửa
              </a>
              <form action="{{ route('categoryDestroy', $category->id) }}" method="POST" 
                    onsubmit="return confirm('Xoá danh mục {{ $category->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="px-3 py-1.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition">
                  <i class="bx bxs-trash"></i> Xoá
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @empty
      <p class="col-span-3 text-center text-gray-500 italic py-6">Chưa có danh mục nào</p>
    @endforelse
  </div>
</div>

{{-- Collapse + Flash --}}
<script>
document.querySelectorAll('[data-collapse]').forEach(btn => {
  btn.addEventListener('click', () => {
    const target = document.querySelector(btn.dataset.collapse)
    const icon = btn.querySelector('i')
    if (!target) return
    target.classList.toggle('hidden')
    icon.classList.toggle('rotate-180')
  })
})
setTimeout(() => {
  const flash = document.getElementById('flash-message')
  if (flash) {
    flash.style.transition = 'opacity 0.5s'
    flash.style.opacity = 0
    setTimeout(() => flash.remove(), 500)
  }
}, 4000)
</script>
@endsection
