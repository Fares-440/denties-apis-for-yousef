<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition()
    {
        return [
            'available_date' => $this->faker->date('Y-m-d', 'now', '+30 years'),
            'available_time' => $this->faker->time('H:i:s'),
        ];
    }
}
