@extends('admin.layouts.layout')

@section('content')
<div class="container py-3">
  <h4 class="fw-bold mb-3">Danh sách biến thể - {{ $product->name }}</h4>

  <form method="POST" action="{{ route('variantStore', $product->id) }}" class="mb-4">
    @csrf
    <div class="row g-2">
      <div class="col-md-2"><input type="text" name="sku" class="form-control" placeholder="SKU" required></div>
      <div class="col-md-2"><input type="number" name="price" class="form-control" placeholder="Giá" required></div>
      <div class="col-md-2"><input type="number" name="compare_price" class="form-control" placeholder="Giá so sánh"></div>
      <div class="col-md-1"><input type="number" step="0.01" name="weight" class="form-control" placeholder="Cân nặng"></div>
      <div class="col-md-1"><input type="number" step="0.01" name="length" class="form-control" placeholder="Dài"></div>
      <div class="col-md-1"><input type="number" step="0.01" name="width" class="form-control" placeholder="Rộng"></div>
      <div class="col-md-1"><input type="number" step="0.01" name="height" class="form-control" placeholder="Cao"></div>
      <div class="col-md-1 form-check d-flex align-items-center">
        <input class="form-check-input me-2" type="checkbox" name="is_active" checked> Active
      </div>
      <div class="col-md-1">
        <button class="btn btn-primary w-100">Thêm</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>SKU</th>
        <th>Attributes</th>
        <th>Price</th>
        <th>Compare</th>
        <th>Weight</th>
        <th>Length</th>
        <th>Width</th>
        <th>Height</th>
        <th>Active</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($variants as $variant)
      <tr>
        <td>{{ $variant->sku }}</td>
        <td><code>{{ json_encode($variant->attributes) }}</code></td>
        <td>{{ number_format($variant->price) }}</td>
        <td>{{ number_format($variant->compare_price) }}</td>
        <td>{{ $variant->weight }}</td>
        <td>{{ $variant->length }}</td>
        <td>{{ $variant->width }}</td>
        <td>{{ $variant->height }}</td>
        <td>{{ $variant->is_active ? '✅' : '❌' }}</td>
        <td>
          <form action="{{ route('product.variants.destroy', [$product->id, $variant->id]) }}" method="POST">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Xoá thật hả?')">Xoá</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
