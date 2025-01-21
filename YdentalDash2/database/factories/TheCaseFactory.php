<?php
namespace Database\Factories;

use App\Models\Student;
use App\Models\TheCase;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class TheCaseFactory extends Factory
{
    protected $model = TheCase::class;

    public function definition()
    {
        $services = Service::all()->pluck('id')->toArray();
        $schedules = Schedule::all()->pluck('id')->toArray();

        $procedureList = [
            'Dental Checkup',
            'Eye Examination',
            'Blood Test',
            'Physical Therapy',
            'Consultation',
            'Imaging',
            'Surgery',
            'Vaccination',
            'Lab Test',
            'Cardiology Evaluation'
        ];

        $minAge = $this->faker->numberBetween(1, 100);
        $maxAge = $this->faker->numberBetween($minAge, 100);

        return [
            'service_id' => $this->faker->randomElement($services),
            'schedules_id' => $this->faker->randomElement($schedules),
            'procedure' => $this->faker->randomElement($procedureList),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Any']),
            'description' => $this->faker->paragraph(),
            'cost' => $this->faker->randomFloat(2, 50, 5000),
            'min_age' => $minAge,
            'max_age' => $maxAge,
            'student_id' => Student::factory(), // Include student_id
        ];
    }
}
