<?php

use Example\TranscarpatianFood\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_date = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone ?? '';
        $this->birth_date = Auth::user()->birth_date ? Auth::user()->birth_date->format('Y-m-d') : '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/', Rule::unique(User::class)->ignore($user->id)],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
        ], [
            'phone.regex' => 'Номер телефону повинен містити від 10 до 15 цифр.',
            'birth_date.before' => 'Дата народження повинна бути в минулому.',
            'birth_date.after' => 'Дата народження повинна бути після 1900 року.',
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Інформація профілю') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Оновіть інформацію профілю вашого облікового запису.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Ім\'я')"/>
            <x-text-input wire:model="name" id="name" name="name" type="text"
                          class="mt-1 block w-full" required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input wire:model="email" id="email" name="email" type="email"
                          class="mt-1 block w-full" required autocomplete="username"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')"/>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Номер телефону')"/>
            <x-text-input wire:model="phone" id="phone" name="phone" type="tel"
                          class="mt-1 block w-full" required autocomplete="tel"/>
            <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
        </div>

        <div>
            <x-input-label for="birth_date" :value="__('Дата народження')"/>
            <x-text-input wire:model="birth_date" id="birth_date" name="birth_date" type="date"
                          class="mt-1 block w-full" required/>
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Зберегти') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Збережено.') }}</p>
            @endif
        </div>
    </form>
</section>
