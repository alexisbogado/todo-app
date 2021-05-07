<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_logout()
    {
        $this->postJson(route('auth.logout'), [ ], $this->getAuthorizationHeader())
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'message',
                ],
            ]);
    }
}
