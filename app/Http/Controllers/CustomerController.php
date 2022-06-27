<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CustomerResource::collection(Customer::all()->where('status', 'A'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $regex = '/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/';
        $findCustomer = Customer::where('dni', $slug);

        // Validate if the query string contain a email pattern
        if (preg_match($regex, $slug)) {
            $findCustomer->orWhere('email', $slug);
        }

        return $findCustomer->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = new Customer;
        $customer->dni = $request->dni;
        $customer->id_reg = $request->id_reg;
        $customer->id_com = $request->id_com;
        $customer->email = $request->email;
        $customer->name = $request->name;
        $customer->last_name = $request->last_name;
        $customer->address = $request->address;

        $statusCode = 200;
        $response = ['success' => true];

        if (!$customer->save()) {
            $statusCode = 204;
            $response['success'] = false;
            $response['data']['message'] = 'El cliente no ha podido ser registrado.';
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $dni
     * @return \Illuminate\Http\Response
     */
    public function destroy($dni = null)
    {
        $statusCode = 200;
        $response = ['success' => true];
        $customer = Customer::where('dni', $dni)
            ->update(['status' => 'trash']);

        if (!$customer) {
            $statusCode = 204;
            $response['success'] = false;
            $response['data']['message'] = 'El cliente no ha podido ser eliminado.';
        }

        return response()->json($response, $statusCode);
    }
}
