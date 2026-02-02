<?php

use App\Models\Religion;
use App\Models\User;
use Illuminate\Support\Arr;

describe('ReligionTest', function () {
    test('religion store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $religion = Religion::factory()->make();
        $response = $this->postJson(route('api.religions.store', $religion->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('religion'))
            ->name->toBe($religion->name);
    });

    test('religion show', function () {
        $religion = Religion::factory()->create([
            'approved' => true,
        ]);
        $response = $this->getJson(route('api.religions.show', ['religion' => $religion]));

        expect($response)
            ->assertOk()
            ->and($response->json('religion'))
            ->id->toBe($religion->id);
    });

    test('religion index', function () {
        $religions = Religion::factory(3)->create();
        $response = $this->getJson(route('api.religions.index'));

        expect($response)->assertOk()
            ->and($response->collect('religions.data')->count())
            ->toBe($religions->count());
    });

    test('religion update', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $religion = Religion::factory()->create();
        $religionPatch = Religion::factory()->make();
        $response = $this->putJson(route('api.religions.update'), [
            'ids' => [$religion->id],
            'data' => Arr::except($religionPatch->toArray(), ['id']),
        ]);

        expect($response)->assertOk();

        $this->assertDatabaseHas('religions', [
            'id' => $religion->id,
            'name' => $religionPatch->name,
            'description' => $religionPatch->description,
        ]);
    });

    test('religion delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $religions = Religion::factory(3)->create();
        $deleteIds = [$religions[0]->id, $religions[1]->id];
        $response = $this->deleteJson(route('api.religions.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('religions', [
                'id' => $id,
            ]);
        }
    });
});
