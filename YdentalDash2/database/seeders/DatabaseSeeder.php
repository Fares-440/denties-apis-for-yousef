<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
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
            SchedulesTableSeeder::class,
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
