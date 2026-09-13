<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Seed the minimum data the app needs to be usable the moment its schema
     * is created: one admin login, and the full equipment inventory (so the
     * borrow form isn't empty). This runs automatically as part of
     * `php artisan migrate` — including NativePHP's local SQLite database,
     * which it configures and migrates on first launch — so no manual
     * `php artisan db:seed` step is required for the app to work.
     *
     * We use the query builder (not Eloquent models) so this migration stays
     * correct even if the models change shape later.
     */
    public function up(): void
    {
        $this->seedAdminUser();
        $this->seedEquipmentInventory();
    }

    public function down(): void
    {
        // Intentionally left blank. Rolling this migration back should not
        // delete the admin account or equipment rows if real attendance,
        // leave, or borrow history now references them.
    }

    private function seedAdminUser(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function seedEquipmentInventory(): void
    {
        $groups = [
            'N' => ['label' => 'Notebook (Laptop)', 'count' => 15],
            'W' => ['label' => 'Webcam', 'count' => 3],
            'LP' => ['label' => 'LCD Projector', 'count' => 3],
            'P' => ['label' => 'Portable PA / Printer', 'count' => 2],
        ];

        foreach ($groups as $prefix => $group) {
            for ($i = 1; $i <= $group['count']; $i++) {
                DB::table('equipment')->updateOrInsert(
                    ['code' => sprintf('%s-%02d', $prefix, $i)],
                    [
                        'category' => $group['label'],
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
};