<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Board;
use App\Models\UserBoard;
use App\Models\Task;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_board_has_a_owner()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $board->owner());
        $this->assertTrue($board->owner()->is($user));
    }

    /** @test */
    public function a_board_has_users()
    {
        $board = Board::factory()->create();
        $users = UserBoard::factory(5)->create([
            'user_id' => User::factory()->create()->id,
            'board_id' => $board->id
        ]);
        
        $this->assertCount($users->count(), $board->fresh()->users);
        $this->assertInstanceOf(Collection::class, $board->users);
    }

    /** @test */
    public function a_board_has_tasks()
    {
        $board = Board::factory()->create();
        $tasks = Task::factory(5)->create([
            'board_id' => $board->id
        ]);
        
        $this->assertCount($tasks->count(), $board->fresh()->tasks);
        $this->assertInstanceOf(Collection::class, $board->tasks);
    }
    
    /** @test */
    public function a_board_has_member_count_attribute()
    {
        $board = Board::factory()->create();
        $users = UserBoard::factory(5)->create([
            'board_id' => $board->id
        ]);

        $this->assertCount($board->member_count, $users);
    }

    /** @test */
    public function a_board_has_is_member_attribute()
    {
        $board = Board::factory()->create();
        $users = UserBoard::factory()->create([
            'user_id' => $this->user->id,
            'board_id' => $board->id
        ]);
        
        $this->actingAs($this->user);
        $this->assertTrue($board->is_member);
    }

    /** @test */
    public function board_is_member_attribute_is_false_if_user_is_not_logged_in()
    {
        $board = Board::factory()->create();
        $users = UserBoard::factory()->create([
            'user_id' => $this->user->id,
            'board_id' => $board->id
        ]);
        
        $this->assertFalse($board->is_member);
    }
}
