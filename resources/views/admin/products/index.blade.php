@extends('admin.layouts.layout')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📦 Danh sách sản phẩm</h4>
        <a href="{{ route('createProduct') }}" class="btn btn-primary">+ Thêm sản phẩm</a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" class="row g-2 mb-3 mt-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Tìm theo tên...">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="">-- Danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="is_active" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100">Lọc</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive mt-5">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Hoạt Động</th>
                    <th>Ngày tạo</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->name }}</td>
                        <td>
                            @foreach($p->categories as $cat)
                                <span class="badge bg-info text-dark">{{ $cat->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ number_format($p->price, 0, ',', '.') }}đ</td>
                        <td>{!! $p->is_active ? '<span class="badge bg-success">Hoạt Động</span>' : '<span class="badge bg-secondary">Dừng</span>' !!}</td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('editProduct', $p->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('destroyProduct', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
