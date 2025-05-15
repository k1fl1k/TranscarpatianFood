@extends('admin.layout')

@section('title', 'Панель приладів')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Панель приладів</h1>
</div>

<!-- Статистика -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Всього замовлень</h6>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-cart3 text-primary fs-3"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-warning">{{ $pendingOrders }} очікують</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Загальний дохід</h6>
                        <h3 class="mb-0">{{ number_format($totalRevenue, 2) }} грн</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-currency-exchange text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Всього товарів</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-box text-info fs-3"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-danger">{{ $outOfStockProducts }} немає в наявності</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Всього користувачів</h6>
                        <h3 class="mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-people text-secondary fs-3"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-primary">{{ $newUsers }} нових за 30 днів</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Останні замовлення -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Останні замовлення</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Всі замовлення</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>№ замовлення</th>
                                <th>Клієнт</th>
                                <th>Сума</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} грн</td>
                                    <td>
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
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Замовлень поки немає</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Популярні товари -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Популярні товари</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Всі товари</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Товар</th>
                                <th>Ціна</th>
                                <th>Наявність</th>
                                <th>Популярність</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ number_format($product->price, 2) }} грн</td>
                                    <td>
                                        @if($product->stock > 0)
                                            <span class="badge bg-success">{{ $product->stock }} шт.</span>
                                        @else
                                            <span class="badge bg-danger">Немає в наявності</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->popularity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Товарів поки немає</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
