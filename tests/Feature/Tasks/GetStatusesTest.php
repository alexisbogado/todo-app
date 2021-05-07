<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetStatusesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function unauthenticated_users_cannot_get_statuses()
    {
        $this->getJson(route('tasks.statuses'))
            ->assertStatus(401);
    }

    /** @test */
    public function users_can_get_statuses()
    {
        $this->getJson(route('tasks.statuses'), $this->getAuthorizationHeader())
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'statuses'
                ],
            ]);
    }
}
