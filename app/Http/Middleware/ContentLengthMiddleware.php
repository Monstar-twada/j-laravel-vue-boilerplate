<?php

namespace App\Http\Middleware;

use Closure;

class ContentLengthMiddleware
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
        $key = 'Content-Length';
        $response = $next($request);

        $content = "";
        if(is_a($response,'Illuminate\Http\JsonResponse')){
            $content = $response->content();
        }else{
            $content = $response->getOriginalContent();
        }

        $responseLength = strlen($content);

        $response->header('Content-Length',$responseLength);

        return $response;
    }
}
