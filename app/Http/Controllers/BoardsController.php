<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CommonResponses;
use App\Models\Board;

class BoardsController extends Controller
{
    use CommonResponses;

    public function index()
    {
        $boards = Board::all()->append('is_member');

        return $this->sendJsonResponse(compact('boards'));
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request, [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        $board = Board::create(array_merge($validator->content, [
            'user_id' => auth()->user()->id,
        ]));

        $board->users()->create([
            'user_id' => auth()->user()->id,
        ]);

        $board = $board->load('owner')->append('is_member');
        
        return $this->sendJsonResponse(compact('board'), 201);
    }

    public function show($id)
    {
        $board = Board::find($id);
        if (!$board) {
            return $this->resourceNotFound('Board');
        }

        $board = $board->load('tasks')->append('is_member');

        return $this->sendJsonResponse(compact('board'));
    }

    public function update(Request $request, $id)
    {
        $board = Board::find($id);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif ($board->owner->id !== auth()->user()->id) {
            return $this->actionNotAllowed();
        }

        $validator = $this->validator($request, [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        $board->append('is_member')
            ->update($validator->content);

        return $this->sendJsonResponse(compact('board'));
    }

    public function destroy($id)
    {
        $board = Board::find($id);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif ($board->owner->id !== auth()->user()->id) {
            return $this->actionNotAllowed();
        }

        $board->delete();
        return $this->successMessage('Board removed successfully');
    }
}
