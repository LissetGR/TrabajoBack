<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use HTMLPurifier;
use HTMLPurifier_Config;
use Mews\Purifier\Facades\Purifier;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = $purifier->purify($value);
        }
        $request->replace($input);
        return $next($request);
    }
}
