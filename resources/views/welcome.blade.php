<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смак Закарпаття – Традиційні Продукти Онлайн</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<!-- Хедер -->
<x-header/>

<!-- Hero Section -->
<section class="bg-image text-white text-center" style="background: url('{{ asset('storage/images/karpaty-bg.jpg') }}') no-repeat center center / cover; height: 500px;">
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center">
        <h1 class="display-4 fw-bold">Справжній смак Карпат – прямо до вашого столу</h1>
        <p class="lead">Домашні продукти від закарпатських господарів</p>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg me-3">Купити зараз</a>
            <a href="#about" class="btn btn-outline-light btn-lg">Дізнатися більше</a>
        </div>
    </div>
</section>

<!-- Категорії -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Категорії продуктів</h2>
        <div class="row text-center">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none text-dark">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded mb-2">
                        <h6>{{ $category->name }}</h6>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Популярні товари -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Популярне зараз</h2>
        <div class="row">
            @foreach($popularProducts as $product)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text fw-bold text-success">{{ number_format($product->price, 2) }} ₴</p>
                            <a href="{{ route('products.show', $product->slug) }}" class="mt-auto btn btn-outline-primary">Детальніше</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Про нас -->
<section id="about" class="py-5 bg-light">
    <div class="container text-center">
        <h2>Про наш магазин</h2>
        <p class="lead">Ми об’єднали господарів із серця Карпат, щоб ви могли насолоджуватись справжніми смаками Закарпаття. Наші продукти – це традиція, смак і душа регіону в кожному укусі.</p>
    </div>
</section>

<!-- Підвал -->
<footer class="bg-dark text-white py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
        <div>
            <h5>Смак Закарпаття</h5>
            <p>© {{ date('Y') }} Всі права захищені</p>
        </div>
        <div>
            <h6>Контакти</h6>
            <p>Email: info@zakarpattiataste.com<br>Тел: +38 (099) 123-45-67</p>
        </div>
        <div>
            <h6>Соцмережі</h6>
            <a href="#" class="text-white me-2">Instagram</a>
            <a href="#" class="text-white me-2">Facebook</a>
            <a href="#" class="text-white">TikTok</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
