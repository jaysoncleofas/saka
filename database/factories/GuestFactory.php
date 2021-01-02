<?php

namespace Database\Factories;

use App\Models\guest;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'middleName' => $this->faker->lastName,
            'contact' => $this->faker->phoneNumber,
            'age' => $this->faker->randomDigitNotNull,
            'address'=> $this->faker->address,
        ];
    }
}
