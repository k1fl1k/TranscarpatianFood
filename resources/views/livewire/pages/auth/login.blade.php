<?php

use Example\TranscarpatianFood\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="mb-4 text-center fw-bold">Вхід до облікового запису</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input wire:model="form.email" id="email" type="email" class="form-control" required autofocus>
            @error('form.email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input wire:model="form.password" id="password" type="password" class="form-control" required>
            @error('form.password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between mb-4">
            <div class="form-check">
                <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input">
                <label class="form-check-label" for="remember">Запам'ятати мене</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="text-decoration-none">
                    Забули пароль?
                </a>
            @endif
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success w-100">Увійти</button>
        </div>

        <div class="text-center mt-4">
            <p class="small text-muted">
                Ще не маєте облікового запису?
                <a href="{{ route('register') }}" wire:navigate class="text-decoration-none">
                    Зареєструватися
                </a>
            </p>
        </div>
    </form>
</div>
