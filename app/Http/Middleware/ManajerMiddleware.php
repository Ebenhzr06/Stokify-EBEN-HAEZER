<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManajerMiddleware
{
/**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && Auth::user()->role === 'Manajer gudang'){

            return $next($request);
        }

        return redirect('/login')->with('error','akses ditolak.');
    }
}
