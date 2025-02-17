<?php

namespace XmlMiddleware;

use Closure;

class XmlWithCDataRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge($request->xml(true, true));

        return $next($request);
    }
}
