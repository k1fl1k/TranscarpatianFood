@extends('layouts.app')

@section('title', 'Оформлення замовлення - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <h1 class="mb-4">Оформлення замовлення</h1>
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Форма оформлення замовлення -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Інформація для доставки</h5>
                    
                    <form action="{{ route('order.process') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Ім'я -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Ім'я *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name ?? old('name') }}" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? old('email') }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Телефон -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Телефон *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? old('phone') }}" required>
                                @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Адреса -->
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Адреса *</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $user->address ?? old('address') }}" required>
                                @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Місто -->
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Місто *</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $user->city ?? old('city') }}" required>
                                @error('city') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Поштовий індекс -->
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Поштовий індекс *</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $user->postal_code ?? old('postal_code') }}" required>
                                @error('postal_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <h5 class="card-title mt-4 mb-3">Спосіб оплати</h5>
                        
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                                <label class="form-check-label" for="payment_cash">
                                    <i class="bi bi-cash me-2"></i> Оплата при отриманні
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                <label class="form-check-label" for="payment_card">
                                    <i class="bi bi-credit-card me-2"></i> Оплата карткою онлайн
                                </label>
                            </div>
                            @error('payment_method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Примітки до замовлення</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Повернутися до кошика
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle"></i> Підтвердити замовлення
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Підсумок замовлення -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Ваше замовлення</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($items as $item)
                            <div class="list-group-item py-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <small class="text-muted">{{ $item->quantity }} x {{ number_format($item->product->price, 2) }} грн</small>
                                            <span class="fw-bold">{{ number_format($item->subtotal, 2) }} грн</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Кількість товарів:</span>
                        <span>{{ $cart->total_items }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Загальна сума:</span>
                        <span class="fs-5 fw-bold">{{ number_format($cart->total_price, 2) }} грн</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
