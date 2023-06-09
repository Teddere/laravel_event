<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start=$this->faker->dateTimeBetween();
        return [
            'title'=>$this->faker->sentence(),
            'start'=>$start,
            'end'=>$this->faker->dateTimeBetween($start),
            'status'=>$this->faker->randomElement(['programs','futures']),
        ];
    }
}
