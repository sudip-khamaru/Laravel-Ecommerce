<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
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
        if( Auth::user()->role->name === "customer" ) {

            return redirect( '/home' )->with( 'message', 'You are not allowed to access' );

        }

        return $next( $request );
    }
}
