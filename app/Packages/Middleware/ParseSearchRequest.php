<?php

namespace App\Packages\Middleware;
use App\Packages\Parsers\RequestCriteriaParser;
use Closure;

class ParseSearchRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->isMethod("GET")){
            $request->merge([ 'search' =>  RequestCriteriaParser :: parseRequest($request)]);
        }

        return $next($request);
    }
}
