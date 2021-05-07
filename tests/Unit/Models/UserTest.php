<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Board;
use App\Models\UserBoard;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_has_boards()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create();

        $this->assertCount(0, $user->fresh()->boards);
        $this->assertCount(0, $board->fresh()->users);

        $user->boards()->attach($board);

        $this->assertTrue($user->boards()->first()->is($board));
        $this->assertTrue($board->users->first()->user()->is($user));
        $this->assertCount(1, $user->fresh()->boards);
        $this->assertCount(1, $board->fresh()->users);
    }
}
