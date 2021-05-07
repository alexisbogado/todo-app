<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_register_without_passing_data()
    {
        $this->postJson(route('auth.register'))
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'username',
                        'email',
                        'password',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_cannot_register_with_existing_name()
    {
        $this->postJson(route('auth.register'), [
            'username' => $this->user->name,
            'email' => 'default@example.com',
            'password' => 'default'
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'username',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_cannot_register_with_existing_email()
    {
        $this->postJson(route('auth.register'), [
            'username' => 'default',
            'email' => $this->user->email,
            'password' => 'default'
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'email',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_cannot_register_with_short_password()
    {
        $this->postJson(route('auth.register'), [
            'username' => 'default',
            'email' => 'default@example.com',
            'password' => '1234'
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
    public function user_cannot_register_with_name_that_contains_spaces()
    {
        $this->postJson(route('auth.register'), [
            'username' => 'default example',
            'email' => 'default@example.com',
            'password' => '1234'
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'contents' => [
                    'errors' => [
                        'username',
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_can_register()
    {
        $this->postJson(route('auth.register'), [
            'username' => 'default',
            'email' => 'default@example.com',
            'password' => 'default',
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'contents' => [
                    'token',
                    'user',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'default',
            'email' => 'default@example.com',
        ]);
    }
}
