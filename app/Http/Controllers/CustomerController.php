<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\Commune;

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
        $findCommune = Commune::where('id_com', $request->id_com)
            ->with(['regions' => function($q) use($request) {
                $q->where('regions.id_reg', '=', $request->id_reg);
            }])
            ->first();

        // Validate if the region is relationated to commune
        if (!$findCommune || count($findCommune->regions) < 1) return response()->json('La commune no tiene relacionada la región seleccionada.');

        $findCustomer = Customer::where('dni', $request->dni)
            ->orWhere('email', $request->email)
            ->first();

        // Validate if the customers is already registered
        if ($findCustomer) return response()->json('El DNI o Email ya se encuentran previamente registrados.');

        // Validate input request
        $validator = Validator::make($request->all(), [
            'dni' => 'required|max:45',
            'id_reg' => 'required',
            'id_com' => 'required',
            'email' => 'required|regex:/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/|max:45',
            'name' => 'required|max:45',
            'address' => 'required|max:255'
        ]);

        if ($validator->fails()) return $validator->errors();

        $customer = new Customer;
        $customer->dni = $request->dni;
        $customer->id_reg = $request->id_reg;
        $customer->id_com = $request->id_com;
        $customer->email = $request->email;
        $customer->name = $request->name;
        $customer->last_name = $request->last_name;
        $customer->address = $request->address;

        return $customer->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $dni
     * @return \Illuminate\Http\Response
     */
    public function destroy($dni = null)
    {
        $customer = Customer::where('dni', $dni)
            ->update(['status' => 'trash']);

        return $customer;
    }
}
