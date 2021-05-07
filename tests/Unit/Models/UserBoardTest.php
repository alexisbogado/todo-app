<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Board;
use App\Models\UserBoard;
use App\Models\Task;

class UserBoardTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_board_has_a_user()
    {
        $user = User::factory()->create();
        $user_board = UserBoard::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $user_board->user());
        $this->assertTrue($user_board->user()->is($user));
    }

    /** @test */
    public function a_user_board_has_a_board()
    {
        $board = Board::factory()->create();
        $user_board = UserBoard::factory()->create([
            'board_id' => $board->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $user_board->board());
        $this->assertTrue($user_board->board()->is($board));
    }
}
