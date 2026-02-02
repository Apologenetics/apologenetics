<?php

namespace Database\Factories;

use App\Models\Doctrine;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $votableModel = fake()->randomElement([Religion::class, Doctrine::class]);

        return [
            'amount' => fake()->numberBetween(-10, 10),
            'user_id' => User::factory(),
            'votable_type' => $votableModel,
            'votable_id' => $votableModel::factory(),
        ];
    }
}
