@extends('admin.layout')

@section('title', 'Управління замовленнями')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Управління замовленнями</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>№ замовлення</th>
                        <th>Дата</th>
                        <th>Клієнт</th>
                        <th>Сума</th>
                        <th>Статус</th>
                        <th>Спосіб оплати</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                {{ $order->customer_name }}
                                @if($order->user)
                                    <span class="badge bg-info">Зареєстрований</span>
                                @else
                                    <span class="badge bg-secondary">Гість</span>
                                @endif
                            </td>
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
                            <td>
                                @if($order->payment_method == 'cash')
                                    <span class="badge bg-secondary">Оплата при отриманні</span>
                                @else
                                    <span class="badge bg-success">Оплата карткою</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Деталі
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Замовлень поки немає</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endsection
