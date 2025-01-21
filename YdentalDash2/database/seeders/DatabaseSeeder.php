<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test',
            'email' => 'admin@admin.com',
            'is_admin' => true,
            'password' => Hash::make('123')
        ]);


        // $faker = Faker::create('ar_SA');

        // Call patient seeder
        $this->call([
            CitiesTableSeeder::class,
            UniversitySeeder::class,
            UsersTableSeeder::class,
            PatientsTableSeeder::class,
            ServicesTableSeeder::class,
            SchedulesTableSeeder ::class,
            StudentsTableSeeder::class,
            TheCasesTableSeeder::class,
            AppointmentsTableSeeder::class,
            VisitsTableSeeder::class,
            ComplaintsTableSeeder::class,
            ChatsTableSeeder::class,
            ReviewsTableSeeder::class,
            ReportsTableSeeder::class,

        ]);
    }
}
