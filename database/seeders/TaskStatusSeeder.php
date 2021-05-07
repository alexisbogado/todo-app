<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task_statuses = TaskStatus::count();

        TaskStatus::when(($task_statuses < 1), function ($query) {
            $query->create([
                'code' => 'to-do',
                'display_name' => 'To Do',
                'order' => 1,
            ]);

            $query->create([
                'code' => 'in-progress',
                'display_name' => 'In Progress',
                'order' => 2,
            ]);
            
            $query->create([
                'code' => 'done',
                'display_name' => 'Done',
                'order' => 3,
            ]);
        });
    }
}
