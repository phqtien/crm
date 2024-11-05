<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('/customers');
    }

    public function fetchCustomers(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::select(['id', 'name', 'phone', 'address', 'email', 'created_at']);
    
            return DataTables::of($customers)
                ->editColumn('created_at', function ($customer) {
                    return $customer->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->addColumn('actions', function () {
                    return '<button class="btn btn-warning editBtn" data-bs-toggle="modal" data-bs-target="#editCustomerModal"><i class="bi bi-pencil-fill"></i></button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $customer = Customer::create($request->only(['name', 'phone', 'address', 'email']));

        return response()->json([
            'message' => 'Customer created successfully.',
            'customer' => $customer
        ], 201);
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

        return response()->json([
            'message' => 'Customer updated successfully.',
            'customer' => $customer
        ], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully.'
        ], 200);
    }
}
