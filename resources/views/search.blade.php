@extends('layouts.app')

@section('title', 'Пошук: ' . $query . ' - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <h1 class="mb-4">Результати пошуку: "{{ $query }}"</h1>

    @if($products->isEmpty())
        <div class="alert alert-info">
            За вашим запитом нічого не знайдено. Спробуйте змінити пошуковий запит.
        </div>

        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">
                Повернутися на головну
            </a>
        </div>
    @else
        <p>Знайдено {{ $products->total() }} товарів</p>

        <div class="row">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
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
@endsection