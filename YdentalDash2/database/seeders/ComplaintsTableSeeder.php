<?php

namespace Database\Seeders;

use App\Models\Complaint;
use Illuminate\Database\Seeder;

class ComplaintsTableSeeder extends Seeder
{
    public function run()
    {
        Complaint::factory()->times(5)->create();
    }
}
