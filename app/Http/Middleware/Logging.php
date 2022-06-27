<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Logging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $message = [
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
            'secure' =>  $request->secure(),
            'method' => $request->method(),
            'path' => $request->path(),
            'segments' => $request->segments()
        ];

        if ($request->input()) {
            $message['data'] = $request->input();
        }

        Log::info(json_encode($message));
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        $message = [
            'status' => $response->status(),
            'statusText' => $response->statusText(),
            'content' => $response->content()
        ];

        if (env('APP_DEBUG')) {
            Log::info(json_encode($message));
        }
    }
}
