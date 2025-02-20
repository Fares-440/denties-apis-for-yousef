<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Patient;
use App\Models\TheCase; // Adjust the namespace as needed
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        $patients = Patient::all()->pluck('id')->toArray();
        $students = Student::all()->pluck('id')->toArray();
        $cases = TheCase::all()->pluck('id')->toArray();
        $schedules = Schedule::all()->pluck('id')->toArray();

        return [
            'patient_id' => $this->faker->randomElement($patients),
            'student_id' => $this->faker->randomElement($students),
            'thecase_id' => $this->faker->randomElement($cases),
            'schedule_id' => $this->faker->randomElement($schedules),
            'status' => $this->faker->randomElement(['بانتظار التأكيد', 'مؤكد', 'مكتمل', 'ملغي']),
        ];
    }
}
