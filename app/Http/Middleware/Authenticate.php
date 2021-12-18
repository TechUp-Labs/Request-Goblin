<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            //return route('loginroute');
         return route('loginroute');
        }
        
        
        //return response(['message'=> 'Unauthenticated']);
        //$request->headers->set('X-Requested-With', 'XMLHttpRequest');
        //return $next($request);
        
    }

    public function handle($request, Closure $next, ...$guards)
    {

        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        if($jwt = $request->cookie('jwt')){

            
            $request->headers->set('Authorization', 'Bearer '.$jwt);

        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
