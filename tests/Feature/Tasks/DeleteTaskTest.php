<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Board;
use App\Models\Task;

class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    private $board;
    private $task;

    public function setUp(): void
    {
        parent::setUp();

        $this->board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->task = $this->board->tasks()->create([
            'user_id' => $this->user->id,
            'description' => 'some task',
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_a_task()
    {
        $this->deleteJson(route('boards.tasks.destroy', [ $this->board->id, $this->task->id ]))
            ->assertStatus(401);
    }
    
    /** @test */
    public function users_cannot_delete_a_task_if_not_a_member()
    {
        $board = Board::factory()->create();
        $task = $board->tasks()->create([
            'user_id' => $this->user->id,
            'description' => 'some task',
        ]);

        $this->deleteJson(route('boards.tasks.destroy', [ $board->id, $task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function members_cannot_delete_a_task_from_non_existing_board()
    {
        $this->deleteJson(route('boards.tasks.destroy', [ PHP_INT_MAX, $this->task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function members_cannot_delete_a_non_existing_task()
    {
        $this->deleteJson(route('boards.tasks.destroy', [ $this->board->id, PHP_INT_MAX ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function members_cannot_delete_a_tasks_from_other_joined_boards()
    {
        $board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson(route('boards.tasks.destroy', [ $board->id, $this->task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_delete_own_board()
    {
        $this->deleteJson(route('boards.tasks.destroy', [ $this->board->id, $this->task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $this->task->id,
            'board_id' => $this->board->id,
        ]);
    }
}
