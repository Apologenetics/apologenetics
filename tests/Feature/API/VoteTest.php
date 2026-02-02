<?php

use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Arr;

describe('VoteTest', function () {
    test('vote store', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vote = Vote::factory()->make();
        $response = $this->postJson(route('api.votes.store', $vote->toArray()));

        expect($response)->assertCreated()
            ->and($response->json('vote'))
            ->amount->toBe($vote->amount);
    });

    test('vote index', function () {
        $votes = Vote::factory(3)->create();
        $response = $this->getJson(route('api.votes.index'));

        expect($response)->assertOk()
            ->and($response->collect('votes.data')->count())
            ->toBe($votes->count());
    });

    test('vote update', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vote = Vote::factory()->create();
        $votePatch = Vote::factory()->make();
        // TODO: Make sure you can only update it to be a upvote or downvote rather than actual integer/user values
        $response = $this->putJson(route('api.votes.update'), [
            'ids' => [[
                'user_id' => $vote->user_id,
                'votable_type' => $vote->votable_type,
                'votable_id' => $vote->votable_id,
            ]],
            'data' => Arr::only($votePatch->toArray(), ['amount']),
        ]);

        expect($response)->assertOk();

        $this->assertDatabaseHas('votes', [
            'user_id' => $vote->user_id,
            'votable_type' => $vote->votable_type,
            'votable_id' => $vote->votable_id,
            'amount' => $votePatch->amount,
        ]);
    });

    test('vote delete', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $votes = Vote::factory(3)->create();
        $deleteIds = [
            [
                'user_id' => $votes[0]->user_id,
                'votable_type' => $votes[0]->votable_type,
                'votable_id' => $votes[0]->votable_id,
            ],
            [
                'user_id' => $votes[1]->user_id,
                'votable_type' => $votes[1]->votable_type,
                'votable_id' => $votes[1]->votable_id,
            ],
        ];
        $response = $this->deleteJson(route('api.votes.delete'), [
            'ids' => $deleteIds,
        ]);

        expect($response)->assertNoContent();

        foreach ($deleteIds as $id) {
            $this->assertDatabaseMissing('votes', $id);
        }
    });
});
