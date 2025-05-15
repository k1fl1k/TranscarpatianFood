<?php

namespace Database\Seeders;

use Example\TranscarpatianFood\Models\Address;
use Example\TranscarpatianFood\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Отримати першого користувача
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Створити основну адресу
        Address::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone ?? '+380991234567',
            'address' => 'вул. Шевченка, 10, кв. 5',
            'city' => 'Ужгород',
            'postal_code' => '88000',
            'country' => 'Україна',
            'is_default' => true,
        ]);
        
        // Створити додаткову адресу
        Address::create([
            'user_id' => $user->id,
            'name' => 'Робоча адреса',
            'phone' => '+380991234567',
            'address' => 'вул. Грушевського, 25, офіс 301',
            'city' => 'Ужгород',
            'postal_code' => '88000',
            'country' => 'Україна',
            'is_default' => false,
        ]);
        
        $this->command->info('Addresses seeded successfully.');
    }
}
