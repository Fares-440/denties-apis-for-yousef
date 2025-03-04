<?php
namespace Database\Seeders;

use App\Models\TheCase;
use Illuminate\Database\Seeder;

class TheCasesTableSeeder extends Seeder
{
    public function run()
    {
        TheCase::factory()->times(5)->create();
    }
}
