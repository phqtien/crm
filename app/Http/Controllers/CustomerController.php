<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('/customers');
    }

    public function fetchCustomers(Request $request)
    {
        $customers = Customer::all();

        $customers->transform(function ($customer) {
            $customer->created_at = $customer->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
            return $customer;
        });

        return response()->json(['customers' => $customers]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        Customer::create($request->only(['name', 'phone', 'address', 'email']));

        return redirect('/customers');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);

        return redirect('/customers');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect('/customers');
    }
}
