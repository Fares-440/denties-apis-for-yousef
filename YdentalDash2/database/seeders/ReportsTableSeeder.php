<?php
namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{
    public function run()
    {
        Report::factory()->times(100)->create();
    }
}
