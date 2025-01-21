<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\Patient;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends BaseFactory
{
    protected $model = Complaint::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::all()->random()->id,
            'complaint_type' => $this->faker->randomElement(['Service Quality', 'Staff Behavior', 'Facility Conditions']),
            'complaint_title' => $this->faker->sentence(3),
            'complaint_desciption' => $this->faker->paragraph(3),
            'complaint_date' => $this->faker->date('Y-m-d', '-5 years'),
            'student_id' => Student::all()->random()->id,
        ];
    }
}
