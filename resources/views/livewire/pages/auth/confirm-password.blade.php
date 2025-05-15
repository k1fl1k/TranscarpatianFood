<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="mb-4 text-center fw-bold">Підтвердження паролю</h2>

    <div class="mb-4 small text-muted">
        Це захищена область додатку. Будь ласка, підтвердіть свій пароль перед продовженням.
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input wire:model="password" id="password" type="password" class="form-control" required>
            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-success">Підтвердити</button>
        </div>
    </form>
</div>
