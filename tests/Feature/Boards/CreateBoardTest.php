<?php

namespace Tests\Feature\Boards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateBoardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cannot_create_a_board()
    {
        $this->postJson(route('boards.store'))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_create_a_board_without_passing_data()
    {
        $this->postJson(route('boards.store'), [ ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_create_a_board_with_empty_title()
    {
        $this->postJson(route('boards.store'), [
            'title' => '',
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'title',
                    ],
                ],
            ]);
    }

    /** @test */
    public function users_cannot_create_a_board_with_a_short_title()
    {
        $this->postJson(route('boards.store'), [
            'title' => 'ab',
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'title',
                    ],
                ],
            ]);
    }

    /** @test */
    public function users_cannot_create_a_board_with_a_long_title()
    {
        $this->postJson(route('boards.store'), [
            'title' => Str::random(300),
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'title',
                    ],
                ],
            ]);
    }

    /** @test */
    public function users_can_create_a_board_with_empty_description()
    {
        $title = 'some title';

        $request = $this->postJson(route('boards.store'), [
            'title' => $title,
        ], $this->getAuthorizationHeader())
            ->assertStatus(201)
            ->assertJsonStructure([
                'contents' => [
                    'board'
                ],
            ]);

        $response = $request->baseResponse->original;

        $this->assertDatabaseHas('boards', [
            'id' => $response['contents']['board']['id'],
            'user_id' => $this->user->id,
            'title' => $title,
        ]);

        $this->assertDatabaseHas('user_boards', [
            'board_id' => $response['contents']['board']['id'],
            'user_id' => $this->user->id,
        ]);
    }
    
    /** @test */
    public function users_cannot_create_a_board_with_a_long_description()
    {
        $this->postJson(route('boards.store'), [
            'title' => 'some title',
            'description' => Str::random(600)
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'description',
                    ],
                ],
            ]);
    }
    
    /** @test */
    public function users_can_create_a_board()
    {
        $title = 'some title';
        $description = 'some description';

        $request = $this->postJson(route('boards.store'), [
            'title' => $title,
            'description' => $description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(201)
            ->assertJsonStructure([
                'contents' => [
                    'board'
                ],
            ]);

        $response = $request->baseResponse->original;

        $this->assertDatabaseHas('boards', [
            'id' => $response['contents']['board']['id'],
            'user_id' => $this->user->id,
            'title' => $title,
            'description' => $description,
        ]);

        $this->assertDatabaseHas('user_boards', [
            'board_id' => $response['contents']['board']['id'],
            'user_id' => $this->user->id,
        ]);
    }
}