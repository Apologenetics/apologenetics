<?php

use App\Models\Doctrine;
use App\Models\User;
use Illuminate\Support\Arr;

describe('DoctrineTest', function () {
    test('doctrine store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doctrine = Doctrine::factory()->make();
        $response = $this->postJson(route('api.doctrines.store', $doctrine->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('doctrine'))
            ->title->toBe($doctrine->title);
    });

    test('doctrine show', function () {
        $doctrine = Doctrine::factory()->create();
        $response = $this->getJson(route('api.doctrines.show', ['doctrine' => $doctrine]));

        expect($response)
            ->assertOk()
            ->and($response->json('doctrine'))
            ->id->toBe($doctrine->id);
    });

    test('doctrine index', function () {
        $doctrines = Doctrine::factory(3)->create();
        $response = $this->getJson(route('api.doctrines.index'));

        expect($response)->assertOk()
            ->and($response->collect('doctrines.data')->count())
            ->toBe($doctrines->count());
    });

    test('doctrine update', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doctrine = Doctrine::factory()->create();
        $doctrinePatch = Doctrine::factory()->make();
        $response = $this->putJson(route('api.doctrines.update'), [
            'ids' => [$doctrine->id],
            'data' => Arr::except($doctrinePatch->toArray(), ['id']),
        ]);

        expect($response)->assertOk();

        $this->assertDatabaseHas('doctrines', [
            'id' => $doctrine->id,
            'title' => $doctrinePatch->title,
            'description' => $doctrinePatch->description,
        ]);
    });

    test('doctrine delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doctrines = Doctrine::factory(3)->create();
        $deleteIds = [$doctrines[0]->id, $doctrines[1]->id];
        $response = $this->deleteJson(route('api.doctrines.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('doctrines', [
                'id' => $id,
            ]);
        }
    });
});
