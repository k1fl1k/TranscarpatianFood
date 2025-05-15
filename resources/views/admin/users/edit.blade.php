@extends('admin.layout')

@section('title', 'Редагувати користувача')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Редагувати користувача</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад до списку
    </a>
</div>

<div class="row">
    <!-- Основна інформація -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Основна інформація</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ім'я *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Роль *</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Клієнт</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Адміністратор</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Зберегти зміни
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Зміна пароля -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Зміна пароля</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.change-password', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Новий пароль *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Підтвердження пароля *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key"></i> Змінити пароль
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Інформація про користувача -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Інформація</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Дата реєстрації:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                <p class="mb-1"><strong>Останній вхід:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : 'Невідомо' }}</p>
                <p class="mb-0"><strong>Email підтверджено:</strong> 
                    @if($user->email_verified_at)
                        <span class="text-success">Так</span>
                    @else
                        <span class="text-danger">Ні</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
