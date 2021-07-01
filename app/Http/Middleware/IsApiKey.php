<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;

class IsApiKey
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
        $api_key = ApiKey::first();

        if (isset(getallheaders()['api_key']) && getallheaders()['api_key'] == $api_key->api_key) {
            return $next($request);
        } else {
            return response()->json([
                "success" => 0,
                "message" => "data dikunci"
            ]);
        }
    }
}
