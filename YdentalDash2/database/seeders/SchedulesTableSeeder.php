<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    public function run()
    {
        Schedule::factory()->times(6)->create();
    }
}
