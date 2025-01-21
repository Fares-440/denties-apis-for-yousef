<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'service_name' => $this->faker->sentence(3),
            'icon' => $this->faker->imageUrl(50, 50),
        ];
    }
}
