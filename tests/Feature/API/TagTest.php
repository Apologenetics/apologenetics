<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Arr;

describe('TagTest', function () {
    test('tag store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tag = Tag::factory()->make();
        $response = $this->postJson(route('api.tags.store', $tag->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('tag'))
            ->name->toBe($tag->name);
    });

    test('tag show', function () {
        $tag = Tag::factory()->create();
        $response = $this->getJson(route('api.tags.show', ['tag' => $tag]));

        expect($response)
            ->assertOk()
            ->and($response->json('tag'))
            ->id->toBe($tag->id);
    });

    test('tag index', function () {
        $tags = Tag::factory(3)->create();
        $response = $this->getJson(route('api.tags.index'));

        expect($response)->assertOk()
            ->and($response->collect('tags.data')->count())
            ->toBe($tags->count());
    });

    test('tag update', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tag = Tag::factory()->create();
        $tagPatch = Tag::factory()->make();
        $response = $this->putJson(route('api.tags.update'), [
            'ids' => [$tag->id],
            'data' => Arr::except($tagPatch->toArray(), ['id']),
        ]);

        expect($response)->assertOk();

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => $tagPatch->name,
        ]);
    });

    test('tag delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tags = Tag::factory(3)->create();
        $deleteIds = [$tags[0]->id, $tags[1]->id];
        $response = $this->deleteJson(route('api.tags.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('tags', [
                'id' => $id,
            ]);
        }
    });
});
