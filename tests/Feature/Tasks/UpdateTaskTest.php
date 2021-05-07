<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Board;
use App\Models\Task;
use App\Models\TaskStatus;

class UpdateTaskTest extends TestCase
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

        $status = TaskStatus::factory()->create();
        $this->task = Task::factory()->create([
            'board_id' => $this->board->id,
            'order' => 1,
            'status_id' => $status->id,
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_task()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_update_task_without_passing_data()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(204);
    }

    /** @test */
    public function users_cannot_update_task_in_a_non_existing_board()
    {
        $this->putJson(route('boards.tasks.update', [ PHP_INT_MAX, $this->task->id ]), [
            'title' => 'some title',
        ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_update_task_if_not_a_member()
    {
        $board = Board::factory()->create();
        $this->putJson(route('boards.tasks.update', [ $board->id, $this->task->id ]), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_task_if_task_is_not_from_the_board()
    {
        $board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $board->users()->create([
            'user_id' => $this->user->id,
        ]);

        $this->putJson(route('boards.tasks.update', [ $board->id, $this->task->id ]), [
            'description' => $this->task->description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function members_cannot_update_task_with_empty_description()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'description' => '',
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
    public function members_cannot_update_task_with_a_short_description()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'description' => 'ab',
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
    public function members_cannot_update_task_with_a_long_title()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'description' => Str::random(200),
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
    public function members_cannot_update_task_with_an_empty_status()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'status_id' => '',
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'status_id',
                    ],
                ],
            ]);
    }

    /** @test */
    public function members_cannot_update_task_with_a_status_as_a_string()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'status_id' => 'some status',
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'status_id',
                    ],
                ],
            ]);
    }

    /** @test */
    public function members_cannot_update_task_with_a_non_existing_status()
    {
        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'status_id' => PHP_INT_MAX,
        ], $this->getAuthorizationHeader())
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'status_id',
                    ],
                ],
            ]);
    }

    /** @test */
    public function members_can_update_task_description()
    {
        $description = 'Some description';

        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'description' => $description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $this->task->id,
            'description' => $description,
        ]);
    }

    /** @test */
    public function members_can_update_task_status()
    {
        $status = TaskStatus::factory()->create();

        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'status_id' => $status->id,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $this->task->id,
            'status_id' => $status->id,
        ]);
    }

    /** @test */
    public function members_can_update_task()
    {
        $description = 'Some description';
        $status = TaskStatus::factory()->create();

        $this->putJson(route('boards.tasks.update', [ $this->board->id, $this->task->id ]), [
            'description' => $description,
            'status_id' => $status->id,
        ], $this->getAuthorizationHeader())
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $this->task->id,
            'description' => $description,
            'status_id' => $status->id,
        ]);
    }
}
