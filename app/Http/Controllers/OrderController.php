<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchBy = $request->input('search_by');

        if ($search) {
            $orders = Order::where($searchBy, 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $orders = Order::paginate(10);
        }

        return view('/orders', compact('orders'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect('/orders');
    }

    public function showCreateNewOrder()
    {
        return view('/newOrder');
    }

    public function searchCustomerByPhone(Request $request)
    {
        $phone = $request->input('phone');
        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json(['customer' => $customer]);
    }

    public function searchProductByName(Request $request)
    {
        $name = $request->input('name');

        $product = Product::where('name', $name)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function createNewOrder(Request $request)
    {
        $validatedData = $request->validate([
            'customer_phone' => 'required|string',
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $customer = Customer::where('phone', $validatedData['customer_phone'])->first();

        $order = Order::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'total_amount' => $validatedData['total_amount'],
            'status' => 'new',
        ]);

        foreach ($validatedData['products'] as $product) {
            $productModel = Product::where('name', $product['name'])->first();

            if (!$productModel) {
                return response()->json(['error' => 'Product not found: ' . $product['name']], 404);
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productModel->id,
                'quantity' => $product['quantity'],
                'unit_price' => $product['price'],
            ]);
        }

        return response()->json(['message' => 'Order created successfully!', 'order_id' => $order->id], 201);
    }

    public function getOrderDetail(Request $request)
    {
        $orderId = $request->input('id');

        $order = Order::find($orderId);

        $customer = Customer::find($order->customer_id);

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        return response()->json([
            'order' => $order,
            'customer' => $customer,
            'order_items' => $orderItems,
        ]);
    }
}
