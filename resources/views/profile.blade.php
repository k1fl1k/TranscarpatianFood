@extends('layouts.app')

@section('title', 'Профіль - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="row">
        <!-- Бокова панель навігації -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="card-title mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="bi bi-person me-2"></i> Особисті дані
                    </a>
                    <a href="#orders" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-bag me-2"></i> Мої замовлення
                    </a>
                    <a href="#addresses" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-geo-alt me-2"></i> Адреси доставки
                    </a>
                    <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-key me-2"></i> Змінити пароль
                    </a>
                    <a href="#delete-account" class="list-group-item list-group-item-action text-danger" data-bs-toggle="list">
                        <i class="bi bi-trash me-2"></i> Видалити обліковий запис
                    </a>
                </div>
            </div>
        </div>

        <!-- Основний контент -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Особисті дані -->
                <div class="tab-pane fade show active" id="profile-info">
                    @include('profile.update-profile-form')
                </div>

                <!-- Замовлення -->
                <div class="tab-pane fade" id="orders">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Історія замовлень</h5>
                        </div>
                        <div class="card-body">
                            @if(count($orders) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>№ замовлення</th>
                                                <th>Дата</th>
                                                <th>Статус</th>
                                                <th>Сума</th>
                                                <th>Дії</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                                    </td>
                                                    <td>{{ number_format($order->total_amount, 2) }} грн</td>
                                                    <td>
                                                        <a href="{{ route('order.confirmation', $order) }}" class="btn btn-sm btn-outline-primary">Деталі</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    У вас ще немає замовлень.
                                    <a href="{{ route('products.index') }}" class="alert-link">Перейти до магазину</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Адреси -->
                <div class="tab-pane fade" id="addresses">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Адреси доставки</h5>
                            <button class="btn btn-sm btn-primary">
                                <i class="bi bi-plus"></i> Додати адресу
                            </button>
                        </div>
                        <div class="card-body">
                            @if(count($addresses) > 0)
                                <div class="row">
                                    @foreach($addresses as $address)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-2 {{ $address->is_default ? 'text-primary' : 'text-muted' }}">
                                                        {{ $address->is_default ? 'Основна адреса' : 'Адреса' }}
                                                    </h6>
                                                    <address class="mb-0">
                                                        {{ $address->name }}<br>
                                                        {{ $address->address }}<br>
                                                        {{ $address->city }} {{ $address->postal_code }}<br>
                                                        {{ $address->country }}<br>
                                                        <abbr title="Телефон">Тел:</abbr> {{ $address->phone ?? 'Не вказано' }}
                                                    </address>
                                                </div>
                                                <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-outline-primary me-2">Редагувати</button>
                                                    @if(!$address->is_default)
                                                        <form action="#" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success">Зробити основною</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    У вас ще немає збережених адрес. Додайте адресу для швидшого оформлення замовлень.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Зміна пароля -->
                <div class="tab-pane fade" id="password">
                    @include('profile.update-password-form')
                </div>

                <!-- Видалення облікового запису -->
                <div class="tab-pane fade" id="delete-account">
                    @include('profile.delete-account-form')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Активація вкладки за хешем в URL
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`a[href="${hash}"]`);
            if (tab) {
                tab.click();
            }
        }

        // Оновлення URL при зміні вкладки
        const tabLinks = document.querySelectorAll('.list-group-item-action');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function (e) {
                history.pushState(null, null, e.target.getAttribute('href'));
            });
        });
    });
</script>
@endpush
@endsection
