<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function(){
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    $firstUser->switchTeam($team = $firstUser->ownedTeams()->create([
        'name' => 'Company Team',
        'personal_team' => false,
    ]));

    $team->users()->attach($secondUser->id);
    $secondUser->switchTeam($team);

    $this->assertEquals($firstUser->currentTeam->id, $secondUser->currentTeam->id);
    $this->actingAs($firstUser, 'sanctum');
});

describe('team', function(){
    test('a user can see other team members and how many todos', function () {
        $response = $this->getJson(route('api.team.index'));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data')
                ->count('data', 2)
            );
    });

    test('a users todos can be returned from the api', function () {
        $user = User::find(2);
        $response = $this->getJson(route('api.team.show', ['user' => $user]));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data')
                ->has('data.id')
                ->has('data.name')
                ->has('data.todos')
                ->has('data.totalTodos')
                ->has('data.completedTodos')
                ->etc()
            );
    });
});
