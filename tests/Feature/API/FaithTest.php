<?php

use App\Models\Faith;
use App\Models\User;
use Illuminate\Support\Arr;

describe('FaithTest', function () {
    test('faith store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $faith = Faith::factory()->make();
        $response = $this->postJson(route('api.faiths.store', $faith->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('faith'))
            ->religion_id->toBe($faith->religion_id);
    });

    test('faith show', function () {
        $faith = Faith::factory()->create();
        $response = $this->getJson(route('api.faiths.show', ['faith' => $faith]));

        expect($response)
            ->assertOk()
            ->and($response->json('faith'))
            ->id->toBe($faith->id);
    });

    test('faith index', function () {
        $faiths = Faith::factory(3)->create();
        $response = $this->getJson(route('api.faiths.index'));

        expect($response)->assertOk()
            ->and($response->collect('faiths.data')->count())
            ->toBe($faiths->count());
    });

    test('faith update', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $faith = Faith::factory()->create();
        $faithPatch = Faith::factory()->make();
        $response = $this->putJson(route('api.faiths.update'), [
            'ids' => [$faith->id],
            'data' => Arr::except($faithPatch->toArray(), ['id']),
        ]);

        expect($response)->assertOk();

        $this->assertDatabaseHas('faiths', [
            'id' => $faith->id,
            'religion_id' => $faithPatch->religion_id,
            'user_id' => $faithPatch->user_id,
        ]);
    });

    test('faith delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $faiths = Faith::factory(3)->create();
        $deleteIds = [$faiths[0]->id, $faiths[1]->id];
        $response = $this->deleteJson(route('api.faiths.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('faiths', [
                'id' => $id,
            ]);
        }
    });
});
