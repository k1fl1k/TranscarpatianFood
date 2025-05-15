@extends('admin.layout')

@section('title', 'Редагувати товар')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Редагувати товар</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад до списку
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Основна інформація -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Назва товару *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Категорія *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Виберіть категорію</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Опис</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Ціна та наявність -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Ціна (грн) *</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Кількість в наявності *</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) == '1' || old('is_active', $product->is_active) === true ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Активний товар</label>
                        </div>
                        <div class="form-text">Неактивні товари не відображаються на сайті.</div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Зображення -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Головне зображення</label>

                        @if($product->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     alt="{{ $product->name }}"
                                     class="img-thumbnail"
                                     style="max-height: 200px;">
                            </div>
                        @endif

                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Залиште порожнім, щоб зберегти поточне зображення.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="images" class="form-label">Додаткові зображення</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Ви можете вибрати кілька зображень.</div>
                    </div>

                    @if($product->images && count($product->images) > 0)
                        <div class="mb-3">
                            <label class="form-label">Поточні додаткові зображення</label>
                            <div class="row">
                                @foreach($product->images as $index => $image)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="card-img-top"
                                                 style="height: 100px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remove_image_{{ $index }}" name="remove_images[]" value="{{ $index }}">
                                                    <label class="form-check-label" for="remove_image_{{ $index }}">
                                                        Видалити
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
