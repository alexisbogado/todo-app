<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function user(Request $request)
    {
        return $this->sendJsonResponse([
            'user' => auth()->user(),
        ]);
    }
}
