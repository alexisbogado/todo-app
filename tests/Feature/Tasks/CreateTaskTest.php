<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\TaskStatus;
use App\Models\Board;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    private $taskStatus;
    private $board;

    public function setUp(): void
    {
        parent::setUp();

        $this->taskStatus = TaskStatus::factory()->create();
        $this->board = Board::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->board->users()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_a_task()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id))
            ->assertStatus(401);
    }

    /** @test */
    public function users_cannot_create_a_task_without_passing_data()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(400);
    }

    /** @test */
    public function users_cannot_create_a_task_if_not_a_member()
    {
        $board = Board::factory()->create();
        $this->postJson(route('boards.tasks.store', $board->id), [ ], $this->getAuthorizationHeader())
            ->assertStatus(403);
    }

    /** @test */
    public function members_cannot_create_a_task_with_non_existing_status()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id), [
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
    public function members_cannot_create_a_task_with_empty_description()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id), [
            'status_id' => $this->taskStatus->id,
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
    public function members_cannot_create_a_task_with_a_short_description()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id), [
            'status_id' => $this->taskStatus->id,
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
    public function members_cannot_create_a_task_with_a_long_title()
    {
        $this->postJson(route('boards.tasks.store', $this->board->id), [
            'status_id' => $this->taskStatus->id,
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
    public function members_cannot_create_a_task_in_a_non_existing_board()
    {
        $this->postJson(route('boards.tasks.store', PHP_INT_MAX), [
            'status_id' => $this->taskStatus->id,
            'description' => $this->board->description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(404);
    }

    /** @test */
    public function members_can_create_a_task()
    {
        $description = 'some task';

        $request = $this->postJson(route('boards.tasks.store', $this->board->id), [
            'status_id' => $this->taskStatus->id,
            'description' => $description,
        ], $this->getAuthorizationHeader())
            ->assertStatus(201)
            ->assertJsonStructure([
                'contents' => [
                    'task'
                ],
            ]);

        $response = $request->baseResponse->original;

        $this->assertDatabaseHas('tasks', [
            'id' => $response['contents']['task']['id'],
            'user_id' => $this->user->id,
            'board_id' => $this->board->id,
            'status_id' => $this->taskStatus->id,
            'description' => $description,
        ]);
    }
}
