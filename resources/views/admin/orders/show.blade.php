@extends('admin.layout')

@section('title', 'Деталі замовлення #' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Замовлення #{{ $order->order_number }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад до списку
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Інформація про замовлення -->
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
                            <strong>Спосіб оплати:</strong> 
                            @if($order->payment_method == 'cash')
                                Оплата при отриманні
                            @else
                                Оплата карткою онлайн
                            @endif
                        </p>
                        @if($order->notes)
                            <p class="mb-0"><strong>Примітки:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Інформація про клієнта</h6>
                        <p class="mb-1">
                            <strong>Клієнт:</strong> 
                            {{ $order->customer_name }}
                            @if($order->user)
                                <span class="badge bg-info">Зареєстрований</span>
                            @else
                                <span class="badge bg-secondary">Гість</span>
                            @endif
                        </p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
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
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                {{ $item->product_name }}
                                                @if($item->product)
                                                    <div class="small text-muted">ID: {{ $item->product->id }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price, 2) }} грн</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }} грн</td>
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
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Управління статусом -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Статус замовлення</h5>
            </div>
            <div class="card-body">
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $statusLabels = [
                        'pending' => 'Очікує обробки',
                        'processing' => 'В обробці',
                        'shipped' => 'Відправлено',
                        'delivered' => 'Доставлено',
                        'cancelled' => 'Скасовано'
                    ];
                @endphp
                
                <div class="mb-4">
                    <p class="mb-2">Поточний статус:</p>
                    <div class="d-inline-block p-2 bg-{{ $statusColors[$order->status] ?? 'secondary' }} text-white rounded">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </div>
                </div>
                
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Змінити статус:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Очікує обробки</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обробці</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Відправлено</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Доставлено</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Скасовано</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle"></i> Оновити статус
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Дії -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Дії</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Друкувати замовлення
                    </button>
                    <a href="mailto:{{ $order->customer_email }}" class="btn btn-outline-info">
                        <i class="bi bi-envelope"></i> Написати клієнту
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
