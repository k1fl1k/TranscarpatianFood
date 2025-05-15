@extends('admin.layout')

@section('title', 'Редагувати категорію')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Редагувати категорію</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад до списку
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Основна інформація -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Назва категорії *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Опис</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Додаткові налаштування -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Порядок сортування</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Категорії з меншим значенням відображаються першими.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) == '1' || old('is_active', $category->is_active) === true ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Активна категорія</label>
                        </div>
                        <div class="form-text">Неактивні категорії не відображаються на сайті.</div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Зображення категорії</label>

                        @if($category->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="img-thumbnail"
                                     style="max-height: 200px;">
                            </div>
                        @endif

                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Залиште порожнім, щоб зберегти поточне зображення.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Зберегти зміни
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
