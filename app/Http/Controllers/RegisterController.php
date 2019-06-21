<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        return response()->json([
            'data' => 'ok'
        ],201);
    }
    //
}
