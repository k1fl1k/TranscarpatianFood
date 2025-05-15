@extends('layouts.app')

@section('title', 'Кошик - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <h1 class="mb-4">Кошик</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cart->isEmpty())
        <div class="alert alert-info">
            Ваш кошик порожній. <a href="{{ route('products.index') }}" class="alert-link">Перейти до товарів</a>
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Товар</th>
                                <th scope="col" class="text-center">Ціна</th>
                                <th scope="col" class="text-center">Кількість</th>
                                <th scope="col" class="text-center">Сума</th>
                                <th scope="col" class="text-center pe-4">Дії</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="img-thumbnail me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="{{ route('products.show', $item->product->slug) }}"
                                                       class="text-decoration-none text-dark">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $item->product->category->name }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ number_format($item->product->price, 2) }} грн
                                    </td>
                                    <td class="text-center align-middle" style="width: 150px;">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary quantity-btn"
                                                        data-action="decrease">-</button>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                       min="1" max="{{ $item->product->stock }}"
                                                       class="form-control text-center quantity-input">
                                                <button type="button" class="btn btn-outline-secondary quantity-btn"
                                                        data-action="increase">+</button>
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center align-middle fw-bold">
                                        {{ number_format($item->subtotal, 2) }} грн
                                    </td>
                                    <td class="text-center align-middle pe-4">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Ви впевнені, що хочете видалити цей товар?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Продовжити покупки
                    </a>
                    <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger"
                       onclick="return confirm('Ви впевнені, що хочете очистити кошик?')">
                        <i class="bi bi-trash"></i> Очистити кошик
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Підсумок замовлення</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Кількість товарів:</span>
                            <span>{{ $cart->total_items }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Загальна сума:</span>
                            <span class="fs-5 fw-bold">{{ number_format($cart->total_price, 2) }} грн</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                            <i class="bi bi-credit-card"></i> Оформити замовлення
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обробка кнопок +/- для кількості товарів
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max'));

                if (this.dataset.action === 'increase' && currentValue < maxValue) {
                    input.value = currentValue + 1;
                } else if (this.dataset.action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                }
            });
        });
    });
</script>
@endpush
@endsection
