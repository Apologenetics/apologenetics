<?php

namespace Database\Factories;

use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faith>
 */
class FaithFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'religion_id' => Religion::factory(),
            'user_id' => User::factory(),
            'date_converted' => $converted = fake()->dateTimeBetween(),
            'reason_converted' => fake()->boolean(75) ? null : fake()->paragraph(),
            'date_reverted' => $reverted = fake()->boolean(75) ? null : fake()->dateTimeBetween(
                startDate: $converted
            ),
            'reason_reverted' => isset($reverted) ? (
                fake()->boolean(75) ? null : fake()->paragraph()
            ) : null,
        ];
    }
}
