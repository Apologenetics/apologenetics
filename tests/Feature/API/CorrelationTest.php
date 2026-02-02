<?php

use App\Models\Correlation;
use App\Models\User;
use Illuminate\Support\Arr;

describe('CorrelationTest', function () {
    test('correlation store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $correlation = Correlation::factory()->make();
        $response = $this->postJson(route('api.correlations.store', $correlation->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('correlation'))
            ->type->toBe($correlation->type->value);
    });

    test('correlation index', function () {
        $correlations = Correlation::factory(3)->create();
        $response = $this->getJson(route('api.correlations.index'));

        expect($response)->assertOk()
            ->and($response->collect('correlations.data')->count())
            ->toBe($correlations->count());
    });

    test('correlation delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $correlations = Correlation::factory(3)->create();
        $deleteIds = [
            [
                'correlatable_from_type' => $correlations[0]->correlatable_from_type,
                'correlatable_from_id' => $correlations[0]->correlatable_from_id,
                'correlatable_to_type' => $correlations[0]->correlatable_to_type,
                'correlatable_to_id' => $correlations[0]->correlatable_to_id,
            ],
            [
                'correlatable_from_type' => $correlations[1]->correlatable_from_type,
                'correlatable_from_id' => $correlations[1]->correlatable_from_id,
                'correlatable_to_type' => $correlations[1]->correlatable_to_type,
                'correlatable_to_id' => $correlations[1]->correlatable_to_id,
            ],
        ];
        $response = $this->deleteJson(route('api.correlations.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('correlations', $id);
        }
    });
});
