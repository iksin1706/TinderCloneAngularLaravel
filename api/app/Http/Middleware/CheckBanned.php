<?php

namespace App\Http\Middleware;

use App\Models\Blockade;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use SebastianBergmann\Diff\Diff;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $ban = Blockade::where('user_id',Auth::user()->id)->orderBy('until','desc')->first();
            if(!$ban) return $next($request);
            $banned_days = Carbon::now()->diffInDays($ban->until, false)+1;
            if ($banned_days>0) {
                Auth::logout();
                return response(
                    'Jestes zablokowany na ' . $banned_days . ' dni. Skontaktuj się z administratorem.'
                , Response::HTTP_FORBIDDEN);
            }
        }


        return $next($request);
    }
}
