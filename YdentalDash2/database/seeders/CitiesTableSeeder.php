<?php
namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            ['name' => 'صنعاء'],
            ['name' => 'تعز'],
            ['name' => 'عدن'],
            ['name' => 'ذمار'],
            ['name' => 'إب'],
            ['name' => 'عمران'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
