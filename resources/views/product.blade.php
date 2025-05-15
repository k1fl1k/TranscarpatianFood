@extends('layouts.app')

@section('title', $product->name . ' - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Головна</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('category.show', $product->category->slug) }}">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             class="d-block w-100 rounded"
                             alt="{{ $product->name }}">
                    </div>
                    @foreach($product->images as $image)
                        <div class="carousel-item">
                            <img src="{{ asset('storage/' . $image) }}"
                                 class="d-block w-100 rounded"
                                 alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
                @if(count($product->images) > 0)
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Попередній</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Наступний</span>
                    </button>
                @endif
            </div>

            <!-- Thumbnails -->
            @if(count($product->images) > 0)
                <div class="row mt-2">
                    <div class="col-3">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             class="img-thumbnail"
                             data-bs-target="#productCarousel"
                             data-bs-slide-to="0"
                             alt="{{ $product->name }}">
                    </div>
                    @foreach($product->images as $index => $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image) }}"
                                 class="img-thumbnail"
                                 data-bs-target="#productCarousel"
                                 data-bs-slide-to="{{ $index + 1 }}"
                                 alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>

            <div class="mb-3">
                <span class="fs-4 fw-bold text-primary">{{ number_format($product->price, 2) }} грн</span>

                @if($product->stock > 0)
                    <span class="badge bg-success ms-2">В наявності</span>
                @else
                    <span class="badge bg-secondary ms-2">Немає в наявності</span>
                @endif
            </div>

            <div class="mb-4">
                {!! nl2br(e($product->description)) !!}
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Кількість:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="quantity" name="quantity" class="form-control"
                                   value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Додати до кошика
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <div class="d-flex align-items-center mb-4">
                <span class="me-2">Категорія:</span>
                <a href="{{ route('category.show', $product->category->slug) }}" class="badge bg-light text-dark text-decoration-none">
                    {{ $product->category->name }}
                </a>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">Схожі товари</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                <img src="{{ asset('storage/' . $relatedProduct->thumbnail) }}"
                                     class="card-img-top"
                                     alt="{{ $relatedProduct->name }}">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h5>
                                <div class="mt-auto">
                                    <p class="fw-bold mb-0">{{ number_format($relatedProduct->price, 2) }} грн</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection