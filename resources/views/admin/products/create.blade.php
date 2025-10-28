@extends('admin.layouts.layout')

@section('content')
<div class="container py-3">
    <h4 class="fw-bold mb-3">➕ Thêm sản phẩm mới</h4>

    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Danh mục</label>
                <select name="category_ids[]" class="form-select" multiple>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Giữ Ctrl (hoặc Cmd) để chọn nhiều danh mục</small>
            </div>

            <div class="col-md-3">
                <label class="form-label">Giá</label>
                <input type="number" name="price" value="{{ old('price') }}" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Giá gốc</label>
                <input type="number" name="compare_price" value="{{ old('compare_price') }}" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="short_description" rows="2" class="form-control">{{ old('short_description') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Ảnh sản phẩm</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-3 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_active" checked>
                <label class="form-check-label">Hiển thị</label>
            </div>

            <div class="col-md-3 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_featured">
                <label class="form-check-label">Nổi bật</label>
            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-success">Lưu</button>
                <a href="{{ route('indexProduct') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </form>
</div>
@endsection
