<?php

namespace Tests\Feature\Boards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Board;

class DeleteBoardTest extends TestCase
{
    use RefreshDatabase;

    private $board;

    public function setUp(): void
    {
        parent::setUp();

        $this->board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_board()
    {
        $this->deleteJson(route('boards.destroy', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_delete_a_non_existing_board()
    {
        $this->deleteJson(route('boards.destroy', PHP_INT_MAX), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_delete_other_user_board()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->deleteJson(route('boards.destroy', $board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_delete_own_board()
    {
        $this->deleteJson(route('boards.destroy', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseMissing('boards', [
            'id' => $this->board->id,
        ]);

        $this->assertDatabaseMissing('user_boards', [
            'board_id' => $this->board->id,
        ]);
    }
}
