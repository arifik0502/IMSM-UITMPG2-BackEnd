<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * NOTE: The admin account and the full equipment inventory are already
     * created automatically by the
     * `2025_02_02_000000_seed_initial_data` migration — that runs as part
     * of `php artisan migrate` itself (including NativePHP's local SQLite
     * setup on first launch), so this seeder is NOT required for the app
     * to function.
     *
     * This seeder just adds a couple of extra demo *employee* accounts,
     * handy for manually testing things like chat between two people on a
     * normal web deployment. It's optional, and safe to run more than once.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo Employee', 'role' => 'employee', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'aisyah@example.com'],
            ['name' => 'Aisyah Employee', 'role' => 'employee', 'password' => Hash::make('password')]
        );
    }
}