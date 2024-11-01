<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchBy = $request->input('search_by');

        if ($search) {
            $customers = Customer::where($searchBy, 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $customers = Customer::paginate(10);
        }

        $customers->getCollection()->transform(function ($customer) {
            $customer->created_at = $customer->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
            return $customer;
        });

        return view('/customers', compact('customers'));
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
