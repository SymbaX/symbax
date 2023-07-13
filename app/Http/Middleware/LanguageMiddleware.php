<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('lang')) {
            $lang = $request->input('lang');
            app()->setLocale($lang);
            session(['lang' => $lang]);
        } elseif (session('lang')) {
            app()->setLocale(session('lang'));
        }

        return $next($request);
    }
}
