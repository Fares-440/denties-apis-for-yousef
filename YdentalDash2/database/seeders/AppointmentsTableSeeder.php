<?php
namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        Appointment::factory()->times(50)->create();
    }
}
