<?php

namespace Tests\Feature\Boards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Board;

class GetBoardTest extends TestCase
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
    public function unauthenticated_users_cannot_get_board()
    {
        $this->getJson(route('boards.show', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_can_get_existing_boad()
    {
        $this->getJson(route('boards.show', $this->board->id), $this->getAuthorizationHeader())
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'board'
                ],
            ]);
    }

    /** @test */
    public function users_cannot_get_non_existing_boad()
    {
        $this->getJson(route('boards.show', PHP_INT_MAX), $this->getAuthorizationHeader())
            ->assertStatus(404);
    }
}
