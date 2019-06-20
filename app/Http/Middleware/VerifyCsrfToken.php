<?php

namespace App\Http\Middleware;

use Closure;  //新增部分
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)

    {

        // Add this:

        if($request->method() == 'POST')

        {

            return $next($request);

        }

 

        if ($request->method() == 'GET' || $this->tokensMatch($request))

        {

            return $next($request);

        }

        throw new TokenMismatchException;

    }
}
