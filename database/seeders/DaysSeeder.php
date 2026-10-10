<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    public function run()
    {
        $days = [
            ['name' => 'Saturday'],
            ['name' => 'Sunday'],
            ['name' => 'Monday'],
            ['name' => 'Tuesday'],
            ['name' => 'Wednesday'],
            ['name' => 'Thursday'],
            ['name' => 'Friday'],
        ];

        foreach ($days as $day) {
            Day::updateOrCreate(
                ['name' => $day['name']],
                ['name' => $day['name']]
            );
        }
    }
}
