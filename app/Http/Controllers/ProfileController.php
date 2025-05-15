<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Example\TranscarpatianFood\Models\User;

class ProfileController extends Controller
{
    /**
     * Показати сторінку профілю користувача
     */
    public function show()
    {
        $user = Auth::user();
        $orders = $user->orders()->orderBy('created_at', 'desc')->get();
        $addresses = $user->addresses()->orderByDesc('is_default')->orderBy('created_at')->get();

        return view('profile', compact('orders', 'addresses'));
    }

    /**
     * Оновити інформацію профілю користувача
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/', Rule::unique(User::class)->ignore($user->id)],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ], [
            'phone.regex' => 'Номер телефону повинен містити від 10 до 15 цифр.',
            'birth_date.before' => 'Дата народження повинна бути в минулому.',
            'birth_date.after' => 'Дата народження повинна бути після 1900 року.',
        ]);

        // Перевірка, чи змінився email
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
            // Тут можна додати відправку листа для підтвердження email
        }

        $user->update($validated);

        return redirect()->route('profile')->with('profile_updated', true);
    }

    /**
     * Оновити пароль користувача
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile', ['#password'])->with('password_updated', true);
    }

    /**
     * Видалити обліковий запис користувача
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('account_deleted', 'Ваш обліковий запис було успішно видалено.');
    }
}
