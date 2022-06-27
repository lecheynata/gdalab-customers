<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Customer
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
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'dni' => 'required'
            ]);

            return $validated;
        } else if  ($request->isMethod('delete')) {
            $customer = Customer::where('dni', 'dni');
        }

        return $next($request);
    }
}
