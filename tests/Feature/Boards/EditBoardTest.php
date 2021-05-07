<?php

namespace Tests\Feature\Boards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Board;

class EditBoardTest extends TestCase
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
    public function unauthenticated_users_cannot_edit_board()
    {
        $this->putJson(route('boards.update', $this->board->id))
            ->assertStatus(401);
    }
    
    /** @test */
    public function users_cannot_edit_a_board_without_passing_data()
    {
        $this->putJson(route('boards.update', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_edit_other_user_board()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->putJson(route('boards.update', $board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_edit_a_non_existing_board()
    {
        $this->putJson(route('boards.update', PHP_INT_MAX), [
            'title' => 'some title',
        ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_edit_a_board_with_empty_title()
    {
        $this->putJson(route('boards.update', $this->board->id), [
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
    public function users_cannot_edit_a_board_with_a_short_title()
    {
        $this->putJson(route('boards.update', $this->board->id), [
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
    public function users_cannot_edit_a_board_with_a_long_title()
    {
        $this->putJson(route('boards.update', $this->board->id), [
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
    public function users_can_edit_with_empty_description()
    {
        $title = 'edited title';

        $this->putJson(route('boards.update', $this->board->id), [
            'title' => $title,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseHas('boards', [
            'id' => $this->board->id,
            'title' => $title,
        ]);
    }
    
    /** @test */
    public function users_cannot_edit_a_board_with_a_long_description()
    {
        $this->putJson(route('boards.update', $this->board->id), [
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
    public function users_can_edit_own_board()
    {
        $title = 'edited title';
        $description = 'edited description';

        $this->putJson(route('boards.update', $this->board->id), [
            'title' => $title,
            'description' => $description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseHas('boards', [
            'id' => $this->board->id,
            'title' => $title,
            'description' => $description,
        ]);
    }
}
