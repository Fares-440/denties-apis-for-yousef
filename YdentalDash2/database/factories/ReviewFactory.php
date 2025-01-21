<?php
namespace Database\Factories;

use App\Models\Review;
use App\Models\Patient;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {

        $patients = Patient::all()->pluck('id')->toArray();
        $students = Student::all()->pluck('id')->toArray();

        return [
            'patient_id' => $this->faker->randomElement($patients),
            'student_id' => $this->faker->randomElement($students),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(3),

        ];
    }
}
