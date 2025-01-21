<?php

namespace Database\Factories;

use App\Models\Visit;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition()
    {
        $procedures = [
            'Check-up',
            'Surgery',
            'Dental Cleaning',
            'X-ray',
            'Blood Test',
            'MRI',
            'Ultrasound',
            'Biopsy',
            'Catheterization',
            'Endoscopy'
        ];

        return [
            'visit_date' => $this->faker->date('Y-m-d', '-5 years'),
            'procedure' => $this->faker->randomElement($procedures),
            'note' => \Faker\Factory::create('ar_SA')->text(100),
            'status' => $this->faker->randomElement(['غير مكتملة', 'مكتملة', 'ملغية']),
            'appointment_id' => Appointment::all()->random()->id,
            'visit_time' => $this->faker->time()
        ];
    }
}
