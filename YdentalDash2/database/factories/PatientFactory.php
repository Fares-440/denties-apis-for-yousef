<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends BaseFactory
{
    protected $model = Patient::class;

    public function definition()
    {

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => bcrypt('password'),
            'id_card' => $this->faker->numerify('########'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber,
            'confirmPassword' => $this->faker->phoneNumber,
            'userType' => 'patient',
            'isBlocked' => $this->faker->randomElement(['yes', 'no']),
        ];
    }
}
