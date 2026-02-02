<?php

namespace Database\Seeders;

use App\Models\Correlation;
use App\Models\Doctrine;
use App\Models\Religion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $christianity = Religion::factory()->create(['name' => 'Christianity']);
        $other = Religion::factory()->create(['name' => 'Other']);

        $user = User::factory()
            ->withFaith($christianity, [
                'date_converted' => '2016-12-18',
                'reason_converted' => 'Reached out to and made into a disciple',
            ])
            ->create([
                'name' => 'Dominic',
                'username' => 'dominicsears',
                'email' => 'dominic@test.com',
                'password' => 'Test123$',
                'gender' => 'male',
                'country_code' => 'en_US',
            ]);

        $users = User::factory(10)
            ->withFaith($other)
            ->create();

        $users->prepend($user);

        // Make doctrines
        $doctrines = collect();
        foreach ([$christianity, $other] as $religion) {
            $createdDoctrines = Doctrine::factory(25)->create();

            $createdDoctrines->each(function ($doctrine) use ($religion) {
                $religion->doctrines()->attach($doctrine);
            });

            $doctrines = $doctrines->merge($createdDoctrines);
        }

        // Make correlations
        $correlationCount = min(15, $doctrines->count());
        foreach ($doctrines->random($correlationCount) as $doctrine) {
            $targetDoctrine = $doctrines->where('id', '!=', $doctrine->id)->random();

            Correlation::factory()->create([
                'correlatable_from_type' => Doctrine::class,
                'correlatable_from_id' => $doctrine->id,
                'correlatable_to_type' => Doctrine::class,
                'correlatable_to_id' => $targetDoctrine->id,
            ]);
        }
    }
}
