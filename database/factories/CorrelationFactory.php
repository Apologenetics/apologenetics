<?php

namespace Database\Factories;

use App\Enums\CorrelationType;
use App\Models\Doctrine;
use App\Models\Religion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Correlation>
 */
class CorrelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromModel = fake()->randomElement([Religion::class, Doctrine::class]);
        $toModel = fake()->randomElement([Religion::class, Doctrine::class]);

        return [
            'correlatable_from_type' => $fromModel,
            'correlatable_from_id' => $fromModel::factory(),
            'correlatable_to_type' => $toModel,
            'correlatable_to_id' => $toModel::factory(),
            'description' => fake()->boolean(75) ? null : fake()->paragraph(),
            'type' => fake()->randomElement(CorrelationType::cases()),
        ];
    }
}
