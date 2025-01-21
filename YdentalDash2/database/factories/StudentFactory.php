<?php
namespace Database\Factories;

use App\Models\City;
use App\Models\Student;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'student_image' => $this->faker->imageUrl(200, 200),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => bcrypt('password'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'level' => $this->faker->randomElement(['Freshman', 'Sophomore', 'Junior', 'Senior']),
            'phone_number' => $this->faker->numerify('##########'),
            'university_card_number' => $this->faker->numerify('############'),
            'university_card_image' => $this->faker->imageUrl(200, 200),
            'userType' => 'Student',
            'isBlocked' => $this->faker->randomElement(['0', '1']),
            'city_id' => City::all()->random()->id,
            'university_id' => University::all()->random()->id,
            'confirmPassword' => $this->faker->phoneNumber,
        ];
    }
}
