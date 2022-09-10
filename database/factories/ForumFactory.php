<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ForumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'     => $this->faker->text(100),
            'user_id' => 2,
            'is_approved' => 0
        ];
    }

     public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => 1,
            ];
        });
    }
}
