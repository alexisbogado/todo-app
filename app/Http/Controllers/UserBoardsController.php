<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonResponses;
use App\Models\Board;

class UserBoardsController extends Controller
{
    use CommonResponses;

    public function store($id)
    {
        $board = Board::find($id);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif ($board->is_member) {
            return $this->actionNotAllowed();
        }

        $user_board = $board->users()->create([
            'user_id' => auth()->user()->id,
        ]);
        
        return $this->sendJsonResponse(compact('user_board'), 201);
    }
    
    public function destroy($id)
    {
        $board = Board::find($id);
        if (!$board) {
            return $this->resourceNotFound('Board');
        } elseif (!$board->is_member || ($board->owner && $board->owner->id === auth()->user()->id)) {
            return $this->actionNotAllowed();
        }

        $board->users()
            ->where('user_id', auth()->user()->id)
            ->delete();

        return $this->successMessage('User leaves successfully');
    }
}
