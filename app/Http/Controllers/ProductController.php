<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('/products');
    }

    public function fetchProducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select(['id', 'name', 'price', 'quantity', 'description', 'created_at']);

            return DataTables::of($products)
                ->editColumn('created_at', function ($product) {
                    return $product->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->addColumn('actions', function () {
                    return '<button class="btn btn-warning editBtn" " data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="bi bi-pencil-fill"></i></button>';
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
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Product::create($request->only(['name', 'price', 'quantity', 'description']));

        return response()->json([
            'message' => 'Product created successfully.',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        return response()->json([
            'message' => 'Product updated successfully.',
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product updated successfully.',
        ], 200);
    }
}
