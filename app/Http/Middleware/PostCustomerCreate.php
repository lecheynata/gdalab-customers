<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Commune;
use App\Models\Customer;

class PostCustomerCreate
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
        $statusCode = 200;
        $response = [
            'success' => true,
            'data' => []
        ];

        if ($request->isMethod('post')) {
            $findCommune = Commune::where('id_com', $request->id_com)
                ->with(['regions' => function($q) use($request) {
                    $q->where('regions.id_reg', '=', $request->id_reg);
                }])
                ->first();

            // Validate if the region is relationated to commune
            if (!$findCommune || count($findCommune->regions) < 1) {
                $response['success'] = false;
                $response['data']['message'] = 'La commune no tiene relacionada la región seleccionada.';
            }

            $findCustomer = Customer::where('dni', $request->dni)
                ->orWhere('email', $request->email)
                ->first();

            // Validate if the customers is already registered
            if ($findCustomer) {
                $response['success'] = false;
                $response['data']['message'] = 'El DNI o Email ya se encuentran previamente registrados.';
            }

            // Validate input request
            $validator = Validator::make($request->all(), [
                'dni' => 'required|max:45',
                'id_reg' => 'required',
                'id_com' => 'required',
                'email' => 'required|regex:/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/|max:45',
                'name' => 'required|max:45',
                'address' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                $response['success'] = false;
                $response['data']['message'] = $validator->errors();
            };
        }

        if (!$response['success']) {
            return response()->json($response);
        }

        return $next($request);
    }
}
