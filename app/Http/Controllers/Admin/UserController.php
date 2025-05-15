<?php

namespace Example\TranscarpatianFood\Http\Controllers\Admin;

use Example\TranscarpatianFood\Http\Controllers\Controller;
use Example\TranscarpatianFood\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Показати список всіх користувачів
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Показати форму для створення нового користувача
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Зберегти нового користувача
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,admin',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Користувача успішно створено.');
    }
    
    /**
     * Показати форму для редагування користувача
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Оновити користувача
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,admin',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Користувача успішно оновлено.');
    }
    
    /**
     * Змінити пароль користувача
     */
    public function changePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Пароль користувача успішно змінено.');
    }
    
    /**
     * Видалити користувача
     */
    public function destroy(User $user)
    {
        // Перевірити, чи не видаляємо самі себе
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Ви не можете видалити свій власний обліковий запис.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Користувача успішно видалено.');
    }
}
