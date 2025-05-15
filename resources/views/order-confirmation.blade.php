@extends('layouts.app')

@section('title', 'Замовлення підтверджено - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="mb-3">Дякуємо за замовлення!</h1>
                    <p class="lead mb-4">Ваше замовлення #{{ $order->order_number }} успішно оформлено.</p>
                    <p class="mb-4">Ми надіслали підтвердження на вашу електронну пошту {{ $order->customer_email }}.</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> На головну
                        </a>
                        @auth
                            <a href="{{ route('profile', ['#orders']) }}" class="btn btn-primary">
                                <i class="bi bi-bag"></i> Мої замовлення
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Деталі замовлення</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Інформація про замовлення</h6>
                            <p class="mb-1"><strong>Номер замовлення:</strong> {{ $order->order_number }}</p>
                            <p class="mb-1"><strong>Дата:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                            <p class="mb-1">
                                <strong>Статус:</strong> 
                                <span class="badge bg-warning">{{ $order->status_label }}</span>
                            </p>
                            <p class="mb-0"><strong>Спосіб оплати:</strong> 
                                @if($order->payment_method == 'cash')
                                    Оплата при отриманні
                                @else
                                    Оплата карткою онлайн
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Інформація про доставку</h6>
                            <p class="mb-1"><strong>Отримувач:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
                            <p class="mb-0"><strong>Адреса:</strong> {{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    
                    <h6>Товари</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-center">Ціна</th>
                                    <th class="text-center">Кількість</th>
                                    <th class="text-end">Сума</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">{{ number_format($item->price, 2) }} грн</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->subtotal, 2) }} грн</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Загальна сума:</th>
                                    <th class="text-end">{{ number_format($order->total_amount, 2) }} грн</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($order->notes)
                        <div class="mt-3">
                            <h6>Примітки до замовлення</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
