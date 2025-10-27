@extends('admin.layouts.layout')

@section('content')
@if (session('success'))
    <div class="alert alert-success mt-2" id="flash-message">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mt-2" id="flash-message">
        {{ session('error') }}
    </div>
@endif



<div class="container py-3">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">📂 Danh mục</h4>
            <a href="{{ route('createCategory') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Thêm danh mục cha
            </a>
        </div>
        <div class="row g-4">
            @foreach ($categories as $category)
    <div class="col-md-4 mb-4">
        <div class="card h-100 category-card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-folder-fill text-warning me-2"></i>{{ $category->name }}
                    </h5>
                    <button 
                        class="btn btn-sm btn-outline-secondary btn-toggle collapsed" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#cat-{{ $category->id }}" 
                        aria-expanded="false"
                    >
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

                <div class="collapse show" id="cat-{{ $category->id }}">
                    @if ($category->children && $category->children->count() > 0)
                        <ul class="list-group list-group-flush mt-2">
                            @foreach ($category->children as $child)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-folder2-open me-2 text-secondary"></i>{{ $child->name }}
                                    </span>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('categoryShow', $child->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bx bxs-eye"></i>
                                        </a>
                                        <a href="{{ route('categoryEdit', $child->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bx bxs-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('categoryDestroy', $child->id) }}" 
                                              class="d-inline"
                                              onsubmit="return confirm('Xóa danh mục {{ $child->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bx bxs-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small fst-italic mt-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i>Không có danh mục con
                        </p>
                    @endif
                </div>
            </div>

            <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                <a href="{{ route('createChildCategory', $category->id) }}" class="btn btn-sm btn-success text-white">
                    <i class="bx bx-plus"></i> Thêm con
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('categoryEdit', $category->id) }}" class="btn btn-sm btn-light border">
                        <i class="bx bxs-edit"></i> Sửa
                    </a>
                    <form method="POST" 
                          action="{{ route('categoryDestroy', $category->id) }}" 
                          class="d-inline"
                          onsubmit="return confirm('Xóa danh mục {{ $category->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger text-white">
                            <i class="bx bxs-trash"></i> Xoá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

        </div>
    </div>
</div>

<style>
/* Card */
.category-card {
    border: none;
    border-radius: 16px;
    background: linear-gradient(145deg, #ffffff, #f9f9f9);
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.category-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(0,0,0,0.12);
}

/* Toggle button */
.btn-toggle {
    border: none;
    background: #f1f3f5;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.btn-toggle i { transition: transform 0.25s ease; }
.btn-toggle[aria-expanded="true"] i { transform: rotate(180deg); color:#0d6efd; }

/* List group item */
.list-group-item {
    border: none;
    border-radius: 8px;
    margin-bottom: 6px;
    padding: 8px 12px;
    background: #f8f9fa;
    font-size: 0.9rem;
}
.list-group-item:hover { background: #edf2f7; }

/* Action icons */
.btn-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1rem;
}
.btn-icon.edit i { color:#495057; }
.btn-icon.view i { color:#0d6efd; }
.btn-icon.delete i { color:#dc3545; }
.btn-icon.delete:hover { background:#f8d7da; }

/* Footer buttons */
.card-footer .btn {
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 0.8rem;
}
</style>
<script>
    // Ẩn sau 60 giây (60000ms)
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500); // xóa hẳn khỏi DOM
        }
    }, 60000); // 1 phút
</script>
@endsection
