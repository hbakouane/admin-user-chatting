<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class OnlineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $cache_value = Cache::put('user-is-online', auth()->id(), \Carbon\Carbon::now()->addMinutes(1));
            $user = User::find(Cache::get('user-is-online'));
            $user->is_online = true;
            $user->save();
        } elseif(!auth()->check() and filled(Cache::get('user-is-online'))) {
            $user = User::find(Cache::get('user-is-online'));
            $user->is_online = false;
            $user->save();
        }
        return $next($request);
    }
}
