<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->check()) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(5);
            Cache::put('user-is-online' . auth()->user()->id, true, $expiresAt);
        }
        $users = User::with(['message' => function($query) {
            $query->orderBy('created_at', 'DESC');
        }])->orderBy('id', 'DESC')->get();
        return view('home', [
            'users' => $users
        ]);
    }
}
