<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('/products');
    }

    public function fetchProducts()
    {
        $products = Product::all();

        $products->transform(function ($product) {
            $product->created_at = $product->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
            return $product;
        });

        return response()->json(['products' => $products]);
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

        return redirect('/products');
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

        return redirect('/products');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/products');
    }
}
