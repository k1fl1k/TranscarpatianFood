@extends('layouts.app')

@section('title', 'Доступ заборонено - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-shield-lock-fill text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="mb-3">Доступ заборонено</h1>
                    <p class="lead mb-4">{{ $exception->getMessage() ?: 'У вас немає прав для перегляду цієї сторінки.' }}</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> На головну
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Повернутися назад
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
