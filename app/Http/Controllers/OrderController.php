<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $query = Order::join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'customers.name as customer_name', 'users.name as user_name');

        if ($search) {
            if ($searchBy === 'customer_name') {
                $query->where('customers.name', 'LIKE', "%{$search}%");
            } elseif ($searchBy === 'user_name') {
                $query->where('users.name', 'LIKE', "%{$search}%");
            } elseif ($searchBy === 'id') {
                $query->where('orders.id', 'LIKE', "%{$search}%");
            }
        }

        $orders = $query->paginate(10);

        $orders->getCollection()->transform(function ($order) {
            $order->created_at = $order->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s'); // Định dạng ngày tháng
            return $order;
        });

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

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productModel->id,
                'quantity' => $product['quantity'],
                'unit_price' => $product['price'],
            ]);

            $productModel->decrement('quantity', $product['quantity']);

            if ($productModel->quantity < 10) {
                Notification::create([
                    'message' => "The product $productModel->name has less than 10 items left in stock!",
                ]);
            }
        }

        return response()->json(['message' => 'Order created successfully!', 'order_id' => $order->id], 201);
    }

    public function getOrderDetail(Request $request)
    {
        $orderId = $request->input('id');

        $order = Order::find($orderId);

        $customer = Customer::find($order->customer_id);

        $orderItems = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.*', 'products.name as product_name')
            ->where('order_items.order_id', $orderId)
            ->get();

        return response()->json([
            'order' => $order,
            'customer' => $customer,
            'order_items' => $orderItems,
        ]);
    }

    public function generateInvoice(Request $request)
    {
        $orderId = $request->input('id');

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([$orderId, 'error' => 'Order not found'], 404);
        }

        $customer = Customer::find($order->customer_id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $orderItems = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.*', 'products.name as product_name')
            ->where('order_items.order_id', $orderId)
            ->get();

        $pdf = PDF::loadView('invoice', compact('order', 'customer', 'orderItems'));

        return $pdf->download('invoice_' . $orderId . '.pdf');

        // return view("/invoice", compact('order', 'customer', 'orderItems'));
    }
}
