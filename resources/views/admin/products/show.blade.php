@extends('admin.layouts.layout')

@section('content')
<div class="p-6 max-w-6xl mx-auto space-y-8">
  {{-- Header --}}
  <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
      🧐 Chi tiết sản phẩm
    </h1>
    <a href="{{ route('admin.product.index') }}" 
       class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
      ⬅ Quay lại
    </a>
  </div>

  {{-- Ảnh chính --}}
  <div class="bg-white rounded-xl shadow p-6 flex gap-6">
    <div class="w-1/3">
      @php
        $main = $product->image ?? optional($product->images->first())->path;
      @endphp
      @if($main)
        <img src="{{ asset('storage/'.$main) }}" 
             class="w-full h-auto rounded-lg border shadow">
      @else
        <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400 rounded-lg">
          Không có ảnh
        </div>
      @endif
    </div>

    <div class="flex-1 space-y-3">
      <h2 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h2>
      <p class="text-sm text-gray-500">{{ $product->slug }}</p>

      <div class="grid grid-cols-2 gap-2 text-sm">
        <div><strong>Danh mục:</strong> {{ $product->category->name ?? '—' }}</div>
        <div><strong>Nhà bán:</strong> {{ $product->vendor->shop_name ?? '—' }}</div>
        <div><strong>Giá bán:</strong> <span class="text-green-600 font-semibold">{{ number_format($product->price, 0, ',', '.') }} VND</span></div>
        <div><strong>Giá gốc:</strong> {{ number_format($product->compare_price, 0, ',', '.') }} VND</div>
        <div><strong>Trạng thái:</strong> 
          <span class="px-2 py-1 text-xs rounded {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
          </span>
        </div>
        <div><strong>Nổi bật:</strong> {{ $product->is_featured ? '✅' : '❌' }}</div>
      </div>

      <div class="text-gray-700 mt-4">
        <strong>Mô tả ngắn:</strong>
        <p class="text-sm mt-1">{{ $product->short_description ?? 'Không có mô tả' }}</p>
      </div>
    </div>
  </div>

  {{-- Ảnh khác --}}
  @if($product->images->count() > 1)
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-lg text-gray-800 mb-4">Ảnh bổ sung</h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      @foreach($product->images as $img)
        @if(!$loop->first)
          <img src="{{ asset('storage/'.$img->path) }}" 
               class="w-full h-40 object-cover rounded-lg border shadow-sm hover:scale-105 transition">
        @endif
      @endforeach
    </div>
  </div>
  @endif

  {{-- Thông tin kỹ thuật --}}
  @if(!empty($product->meta))
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-lg text-gray-800 mb-4">Thông tin kỹ thuật</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
      @foreach($product->meta as $k => $v)
        <div><strong>{{ $k }}:</strong> {{ $v }}</div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Biến thể --}}
  @if($product->variants->count())
  <div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-lg text-gray-800 mb-4">Biến thể</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700 border">
            <thead class="bg-gray-100 text-gray-600">
              <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Tổ hợp</th> {{-- 🆕 --}}
                <th class="px-4 py-2">SKU</th>
                <th class="px-4 py-2">Giá</th>
                <th class="px-4 py-2">Giá gốc</th>
                <th class="px-4 py-2">Trạng thái</th>
                <th class="px-4 py-2">Ảnh</th>
              </tr>
            </thead>
            <tbody>
              @foreach($product->variants as $i => $v)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $i + 1 }}</td>
                <td class="px-4 py-2 text-gray-800">{{ $v->attribute_label_combo }}</td> {{-- 🆕 --}}
                <td class="px-4 py-2">{{ $v->sku }}</td>
                <td class="px-4 py-2">{{ number_format($v->price, 0, ',', '.') }} VND</td>
                <td class="px-4 py-2">{{ number_format($v->compare_price, 0, ',', '.') }} VND</td>
                <td class="px-4 py-2">{{ $v->is_active ? '✅' : '❌' }}</td>
                <td class="px-4 py-2">
                  @php
                    $img = optional($v->images->first())->path;
                  @endphp
                  @if($img)
                    <img src="{{ asset('storage/'.$img) }}" class="w-14 h-14 rounded-md object-cover border">
                  @else
                    <div class="w-14 h-14 bg-gray-100 border flex items-center justify-center text-gray-400 text-xs">N/A</div>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          
    </div>
  </div>
  @endif
</div>
@endsection
