<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    /**
     * The user instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
        
        $this->withoutExceptionHandling();

        $this->user = User::factory()->create([
            'password' => Hash::make('admin')
        ]);
    }

    /**
     * Get current user's authorization header
     *
     * @return array
     */
    protected function getAuthorizationHeader(): array
    {
        $token = JWTAuth::fromUser($this->user);

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }
}
