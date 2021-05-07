<?php

namespace Tests\Feature\UserBoards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Board;

class UserJoinsToBoardTest extends TestCase
{
    use RefreshDatabase;

    private $board;

    public function setUp(): void
    {
        parent::setUp();

        $this->board = Board::factory()->create();
    }

    /** @test */
    public function unauthenticated_users_cannot_join_to_a_board()
    {
        $this->postJson(route('boards.users.store', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_join_to_a_non_existing_board()
    {
        $this->postJson(route('boards.users.store', PHP_INT_MAX), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_join_if_already_a_member()
    {
        $this->board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->postJson(route('boards.users.store', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_join()
    {
        $this->postJson(route('boards.users.store', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(201)
            ->assertJsonStructure([
                'contents' => [
                    'user_board',
                ],
            ]);

        $this->assertDatabaseHas('user_boards', [
            'board_id' => $this->board->id,
            'user_id' => $this->user->id,
        ]);
    }
}
