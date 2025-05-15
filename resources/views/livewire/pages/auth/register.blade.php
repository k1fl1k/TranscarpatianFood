<?php

use Example\TranscarpatianFood\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_date = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/', 'unique:' . User::class],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Номер телефону повинен містити від 10 до 15 цифр.',
            'birth_date.before' => 'Дата народження повинна бути в минулому.',
            'birth_date.after' => 'Дата народження повинна бути після 1900 року.',
            'email.unique' => 'Користувач з такою електронною адресою вже існує.',
            'phone.unique' => 'Користувач з таким номером телефону вже існує.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="mb-4 text-center fw-bold">Створення облікового запису</h2>

    <form wire:submit="register">
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Ім'я</label>
            <input wire:model="name" id="name" type="text" class="form-control" required autofocus>
            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input wire:model="email" id="email" type="email" class="form-control" required>
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Phone Number -->
        <div class="mb-3">
            <label for="phone" class="form-label">Номер телефону</label>
            <input wire:model="phone" id="phone" type="tel" class="form-control" placeholder="+380XXXXXXXXX" required>
            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Birth Date -->
        <div class="mb-3">
            <label for="birth_date" class="form-label">Дата народження</label>
            <input wire:model="birth_date" id="birth_date" type="date" class="form-control" required>
            @error('birth_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input wire:model="password" id="password" type="password" class="form-control" required>
            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Підтвердження паролю</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-control" required>
            @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success w-100">Зареєструватися</button>
        </div>

        <div class="text-center mt-4">
            <p class="small text-muted">
                Вже маєте обліковий запис?
                <a href="{{ route('login') }}" wire:navigate class="text-decoration-none">
                    Увійти
                </a>
            </p>
        </div>
    </form>
</div>
