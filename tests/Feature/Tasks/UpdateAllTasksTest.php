<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Board;
use App\Models\Task;

class UpdateAllTasksTest extends TestCase
{
    use RefreshDatabase;

    private $board;
    private $tasks;

    public function setUp(): void
    {
        parent::setUp();

        $this->board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $this->board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->tasks = Task::factory(5)->create([
            'board_id' => $this->board->id,
            'order' => 1,
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_tasks()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_update_tasks_without_passing_data()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_update_tasks_in_a_non_existing_board()
    {
        $this->putJson(route('boards.tasks.updateAll', PHP_INT_MAX), [
            'title' => 'some title',
        ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_update_tasks_if_not_a_member()
    {
        $board = Board::factory()->create();
        $this->putJson(route('boards.tasks.updateAll', $board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_tasks_with_empty_list()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id), [
            'tasks' => [ ]
        ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_update_tasks_with_non_array_tasks()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id), [
            'tasks' => 'some value'
        ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_update_tasks_with_a_non_existing_task_in_the_list()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id), [
            'tasks' => [
                [
                    'id' => PHP_INT_MAX,
                    'order' => 1,
                ]
            ]
        ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_update_tasks_with_a_non_integer_order()
    {
        $this->putJson(route('boards.tasks.updateAll', $this->board->id), [
            'tasks' => [
                [
                    'id' => $this->tasks->first()->id,
                    'order' => 'some order',
                ]
            ]
        ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }
    
    /** @test */
    public function users_can_update_tasks()
    {
        $this->tasks->first()->order = 2;

        $request = $this->putJson(route('boards.tasks.updateAll', $this->board->id), [
            'tasks' => $this->tasks,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $this->tasks->first()->id,
            'order' => 2,
        ]);
    }
}
