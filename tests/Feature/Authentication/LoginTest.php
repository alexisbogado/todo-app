<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_login_without_passing_data()
    {
        $this->postJson(route('auth.login'))
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'email',
                        'password',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_cannot_login_with_wrong_credentials()
    {
        $this->postJson(route('auth.login'), [
            'email' => $this->user->email,
            'password' => 'default'
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'password',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $this->postJson(route('auth.login'), [
            'email' => $this->user->email,
            'password' => 'admin',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'contents' => [
                    'token',
                    'user',
                ],
            ]);
    }
}
