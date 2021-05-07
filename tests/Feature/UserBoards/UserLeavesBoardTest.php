<?php

namespace Tests\Feature\UserBoards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Board;

class UserLeavesBoardTest extends TestCase
{
    use RefreshDatabase;

    private $board;

    public function setUp(): void
    {
        parent::setUp();

        $this->board = Board::factory()->create();
    }

    /** @test */
    public function unauthenticated_users_cannot_leave_a_board()
    {
        $this->deleteJson(route('boards.users.destroy', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_leave_a_non_existing_board()
    {
        $this->deleteJson(route('boards.users.destroy', PHP_INT_MAX), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_leave_if_not_a_member()
    {
        $this->deleteJson(route('boards.users.destroy', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_leave_if_is_the_owner()
    {
        $board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->deleteJson(route('boards.users.destroy', $board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function members_can_leave()
    {
        $this->board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson(route('boards.users.destroy', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseMissing('user_boards', [
            'board_id' => $this->board->id,
            'user_id' => $this->user->id,
        ]);
    }
}
