<?php

namespace Database\Factories;

use App\Models\Faith;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'gender' => fake()->boolean() ? 'male' : 'female',
            'faith_id' => null,
            'country_code' => fake()->countryCode(),
            'password' => static::$password ??= 'password',
            'remember_token' => Str::random(10),
//            'two_factor_secret' => Str::random(10),
//            'two_factor_recovery_codes' => Str::random(10),
//            'two_factor_confirmed_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model does not have two-factor authentication configured.
     */
    public function withoutTwoFactor(): static
    {
        return $this->state(fn(array $attributes) => [
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Indicate that the user has a faith.
     */
    public function withFaith(?\App\Models\Religion $religion = null, array $faithAttributes = []): static
    {
        return $this->afterCreating(function (User $user) use ($religion, $faithAttributes) {
            if (empty($faithAttributes)) {
                $faithAttributes = [
                    'religion_id' => null,
                ];
            }

            $faithData = array_merge([
                'user_id' => $user->id,
            ], $faithAttributes);

            if ($religion) {
                $faithData['religion_id'] = $religion->id;
            } else {
                $religion = Religion::factory()->create();
                $faith['religion_id'] = $religion->id;
            }

            $faith = Faith::factory()->create($faithData);

            $user->update(['faith_id' => $faith->id]);
        });
    }
}
