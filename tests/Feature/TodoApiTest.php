<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

describe('api', function () {
    test('the api is working', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');
        $response = $this->get(route('api.user'));
        $this->assertEquals($response->json('name'), $user->name);
    });

    test('a user can get their todos from the api', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');
        \App\Models\Todo::factory(5)->for($user)->create();
        $response = $this->getJson(route('api.todos.index'));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('todos')
                ->count('todos', 5)
            );
    });
    test('a user can get their create a todo via api', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');

        $response = $this->postJson(route('api.todos.create'), [
            'title' => 'Test',
            'uuid' => 'ABC123',
        ]);
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json->has('todos')
                ->count('todos', 1)
            );
    });

    test('title is required', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');

        $response = $this->postJson(route('api.todos.create'), [
            'uuid' => 'ABC123',
        ]);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    });

    test('uuid is required', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');

        $response = $this->postJson(route('api.todos.create'), [
            'title' => 'ABC123',
        ]);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('uuid');
    });

    test('completed_at and currently_working_on get stored too', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');

        $time = now()->toString();
        $response = $this->postJson(route('api.todos.create'), [
            'title' => 'ABC123',
            'uuid' => 'test',
            'currently_working_on' => true,
            'completed_at' => $time,
        ]);
        $response
            ->assertStatus(201);

        $this->assertDatabaseHas('todos', [
            'title' => 'ABC123',
            'uuid' => 'test',
            'currently_working_on' => true,
            'completed_at' => $time,
        ]);

    });

    test('a user can edit a todo', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');

        \App\Models\Todo::factory()->for(User::first())->create(['title' => 'ABC123']);

        $alreadyCreated = \App\Models\Todo::first();

        $this->postJson(route('api.todos.edit', ['todo' => $alreadyCreated]), [
            'title' => 'New title',
        ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'New title',
        ]);

    });

    test('a user can delete a todo', function () {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create(), 'sanctum');
        \App\Models\Todo::factory(5)->for($user)->create();

        $todo = \App\Models\Todo::first();

        $response = $this->deleteJson(route('api.todos.delete', ['todo' => $todo]));

        $response->assertStatus(200);

        $this->assertDatabaseCount('todos', 4);
    });

});

describe('todos', function () {
    test('a user can have todos', function () {
        $user = User::factory()->withPersonalTeam()->create();
        \App\Models\Todo::factory(5)->for($user)->create();

        $this->assertDatabaseCount('todos', 5);
    });
});
