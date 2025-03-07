<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with(['timesheets','projects'])->get();
        return response()->json($users);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->load('timesheets', 'projects');
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
