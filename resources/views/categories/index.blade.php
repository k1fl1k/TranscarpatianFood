@extends('layouts.app')

@section('title', 'Категорії продуктів - Смак Закарпаття')

@section('content')
<div class="container py-5 min-vh-100">
    <h1 class="mb-4 text-center">Категорії продуктів</h1>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <a href="{{ route('category.show', $category->slug) }}">
                        <img src="{{ asset('storage/' . $category->image) }}"
                             class="card-img-top"
                             alt="{{ $category->name }}">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <a href="{{ route('category.show', $category->slug) }}"
                               class="text-decoration-none text-dark">
                                {{ $category->name }}
                            </a>
                        </h5>
                        @if($category->description)
                            <p class="card-text text-muted">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection