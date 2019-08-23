<?php

namespace App\Http\Controllers;

use App\User;

class UsersController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        $managers = User::managers()->get();

        return view('users.index', compact('managers'));
    }
}
