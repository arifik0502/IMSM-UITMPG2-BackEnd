<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'N' => ['label' => 'Notebook (Laptop)', 'count' => 15],
            'W' => ['label' => 'Webcam', 'count' => 3],
            'LP' => ['label' => 'LCD Projector', 'count' => 3],
            'P' => ['label' => 'Portable PA / Printer', 'count' => 2],
        ];

        foreach ($groups as $prefix => $group) {
            for ($i = 1; $i <= $group['count']; $i++) {
                $code = sprintf('%s-%02d', $prefix, $i);

                Equipment::updateOrCreate(
                    ['code' => $code],
                    ['category' => $group['label'], 'is_active' => true]
                );
            }
        }
    }
}
