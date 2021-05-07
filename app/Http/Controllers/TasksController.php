<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonResponses;
use App\Models\TaskStatus;
use App\Models\Board;

class TasksController extends Controller
{
    use CommonResponses;

    public function statuses()
    {
        $statuses = TaskStatus::orderBy('order')->get();

        return $this->sendJsonResponse(compact('statuses'));
    }

    public function store(Request $request, $boardId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif (!$board->is_member) {
            return $this->actionNotAllowed();
        }

        $validator = $this->validator($request, [
            'status_id' => 'required|integer|exists:task_statuses,id',
            'description' => 'required|string|min:3|max:100',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        $task = $board->tasks()->create(array_merge($validator->content, [
            'user_id' => auth()->user()->id
        ]));
        $task = $task->load('user');

        return $this->sendJsonResponse(compact('task'), 201);
    }

    public function update(Request $request, $boardId, $taskId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif (!$board->is_member) {
            return $this->actionNotAllowed();
        }

        $validator = $this->validator($request, [
            'status_id' => 'sometimes|required|integer|exists:task_statuses,id',
            'description' => 'sometimes|required|string|min:3|max:100',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        } else if (!$validator->content) {
            return $this->sendJsonResponse([
                'message' => 'No content to update'
            ], 204);
        }

        $task = $board->tasks()->find($taskId);
        if (!$task) {
            return $this->resourceNotFound('Task');
        }

        $task->update($validator->content);
        
        return $this->sendJsonResponse(compact('task'));
    }

    public function updateAll(Request $request, $boardId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif (!$board->is_member) {
            return $this->actionNotAllowed();
        }
        
        $validator = $this->validator($request, [
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|integer',
        ], true);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        $tasks = $validator->content['tasks'];
        
        for ($i = 0, $tasks_count = count($tasks); $i < $tasks_count; $i++) {
            $task = ((object) $tasks[$i]);
            
            if (!$task->id) {
                continue;
            }

            $board->tasks()
                ->find($task->id)
                ->update([
                    'order' => $task->order,
                ]);
        }

        return $this->successMessage('Tasks saved successfully');
    }

    public function destroy($boardId, $taskId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif (!$board->is_member) {
            return $this->actionNotAllowed();
        }

        $task = $board->tasks()->find($taskId);
        if (!$task) {
            return $this->resourceNotFound('Task');
        }

        $task->delete();
        return $this->successMessage('Task removed successfully');
    }
}
