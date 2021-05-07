<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_task_is_from_an_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $task->user());
        $this->assertTrue($task->user()->is($user));
    }
}
