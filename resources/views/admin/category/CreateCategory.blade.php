@extends('admin.layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card modern-card">
                <div class="card-header modern-header">
                    <h4 class="mb-0 fw-semibold">Thêm danh mục</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categoryStore') }}" method="POST">
                        @csrf

                        <!-- Tên danh mục -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control modern-input" id="name" name="name" placeholder="Nhập tên danh mục" required>
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control modern-input" id="slug" name="slug" placeholder="Tự sinh nếu bỏ trống">
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control modern-input" id="description" name="description" rows="3" placeholder="Mô tả ngắn gọn..."></textarea>
                        </div>

                    

                        <!-- Thứ tự -->
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Thứ tự</label>
                            <input type="number" class="form-control modern-input" id="sort_order" name="sort_order" value="0" min="0">
                        </div>

                        <!-- Trạng thái -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input modern-check" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Hoạt động</label>
                        </div>

                        <!-- Nút -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('indexCategory') }}" class="btn btn-outline-modern">Hủy</a>
                            <button type="submit" class="btn btn-modern">Tạo danh mục</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS tối giản hiện đại -->
<style>
    .modern-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .modern-header {
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        padding: 16px 20px;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }

    .modern-input {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 10px 12px;
        transition: all 0.2s ease;
    }

    .modern-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }

    .modern-check {
        border-radius: 4px;
    }

    .btn-modern {
        background: #111827;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-modern:hover {
        background: #1f2937;
    }

    .btn-outline-modern {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 18px;
        color: #374151;
        background: #fff;
        transition: all 0.2s ease;
    }

    .btn-outline-modern:hover {
        background: #f3f4f6;
    }
</style>
@endsection
