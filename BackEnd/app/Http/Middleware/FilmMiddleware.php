<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Exceptions\FilmInFutureException;

class FilmMiddleware
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
        $this_year = \Carbon\Carbon::now()->year;
        if ($request->release_year > ($this_year + 1)) { 
            throw new FilmInFutureException("La date du film est dans le futur.");
        } 

        return $next($request);
    }
}
