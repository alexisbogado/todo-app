<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticatedUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_get_info_as_guest()
    {
        $this->getJson(route('user'))
            ->assertStatus(401);
    }

    /** @test */
    public function user_can_get_info()
    {
        $this->getJson(route('user'), $this->getAuthorizationHeader())
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'user',
                ],
            ]);
    }
}
