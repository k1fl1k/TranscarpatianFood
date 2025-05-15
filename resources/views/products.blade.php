@extends('layouts.app')

@section('title', 'Всі продукти - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <h1 class="mb-4">Всі продукти</h1>

    <div class="row">
        <!-- Filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Фільтри</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="category" class="form-label">Категорія</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">Всі категорії</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                            {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sort" class="form-label">Сортування</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="popularity-desc" {{ $sortBy == 'popularity' && $sortDirection == 'desc' ? 'selected' : '' }}>
                                    За популярністю
                                </option>
                                <option value="price-asc" {{ $sortBy == 'price' && $sortDirection == 'asc' ? 'selected' : '' }}>
                                    Ціна: від низької до високої
                                </option>
                                <option value="price-desc" {{ $sortBy == 'price' && $sortDirection == 'desc' ? 'selected' : '' }}>
                                    Ціна: від високої до низької
                                </option>
                                <option value="name-asc" {{ $sortBy == 'name' && $sortDirection == 'asc' ? 'selected' : '' }}>
                                    Назва: А-Я
                                </option>
                                <option value="name-desc" {{ $sortBy == 'name' && $sortDirection == 'desc' ? 'selected' : '' }}>
                                    Назва: Я-А
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Застосувати</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-9">
            @if($products->isEmpty())
                <div class="alert alert-info">
                    Товари не знайдено.
                </div>
            @else
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                         class="card-img-top"
                                         alt="{{ $product->name }}">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted mb-0">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                    <div class="mt-auto pt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }} грн</span>
                                            @if($product->stock > 0)
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-cart-plus"></i> До кошика
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Немає в наявності</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection