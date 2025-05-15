<header class="bg-light border-bottom sticky-top py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="/" class="text-decoration-none text-dark fs-4 fw-bold">Смак Закарпаття</a>

        <form class="d-flex w-50" method="GET" action="{{ route('search') }}">
            <input class="form-control me-2" type="search" name="q" placeholder="Пошук серед 300+ продуктів" aria-label="Пошук">
            <button class="btn btn-outline-success" type="submit">Пошук</button>
        </form>

        <div>
            @auth
                <div class="dropdown">
                    <a href="#" class="me-3 text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Профіль</a></li>
                        @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Адмін-панель</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Вийти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="me-3 text-decoration-none">Увійти</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="text-decoration-none">Кошик 🛒</a>
        </div>
    </div>
</header>
