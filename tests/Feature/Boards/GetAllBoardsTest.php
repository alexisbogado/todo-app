<?php

namespace Tests\Feature\Boards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllBoardsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function unauthenticated_users_cannot_get_boards()
    {
        $this->getJson(route('boards.index'))
            ->assertStatus(401);
    }

    /** @test */
    public function users_can_get_boads()
    {
        $this->getJson(route('boards.index'), $this->getAuthorizationHeader())
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'boards'
                ],
            ]);
    }
}
