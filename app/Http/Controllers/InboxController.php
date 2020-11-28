<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class InboxController extends Controller
{

    public function index() {
        if(auth()->check()) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(5);
            Cache::put('user-is-online' . auth()->user()->id, true, $expiresAt);
        } else {
            abort(403);
        }
        $users = User::with(['message' => function($query) {
            $query->orderBy('created_at', 'DESC');
        }])->orderBy('id', 'DESC')->get();

        if (auth()->user()->is_admin == false) {
            $messages = \App\Models\Message::where('user_id', auth()->id())->orWhere('receiver', auth()->id())->orderBy('id', 'DESC')->get();
        }

        return view('home', [
            'users' => $users,
            'messages' => $messages ?? null
        ]);
    }

    public function show($id) {
        if (auth()->user()->is_admin == false) {
            abort(404);
        }

        $sender = User::findOrFail($id);

        $users = User::with(['message' => function($query) {
            $query->orderBy('created_at', 'DESC');
        }])->orderBy('id', 'DESC')->get();

        if (auth()->user()->is_admin == false) {
            $messages = \App\Models\Message::where('user_id', auth()->id())->orWhere('receiver', auth()->id())->orderBy('id', 'DESC')->get();
        } else {
            $messages = \App\Models\Message::where('user_id', $sender)->orWhere('receiver', $sender)->orderBy('id', 'DESC')->get();
        }

        return view('show', [
            'users' => $users,
            'messages' => $messages,
            'sender' => $sender
        ]);
    }

}
