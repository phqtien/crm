<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        h1, h2, h3 {
            text-align: center;
            margin: 0;
            padding: 10px 0;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 30px;
            font-size: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .customer-info, .order-info {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .customer-info p, .order-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Invoice</h1>
        <div class="invoice-header">
            <h5>Invoice ID: {{ $order->id }}</h5>
            <h5>Created at: {{ $order->created_at }}</h5>
        </div>

        <h3>Customer Information</h3>
        <div class="customer-info">
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}</p>
        </div>

        <h3>Order Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($orderItems as $item)
                @php
                $totalPrice = $item->quantity * $item->unit_price;
                $grandTotal += $totalPrice;
                @endphp
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($totalPrice, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="total">Grand Total: {{ number_format($grandTotal, 2) }}</h3>
    </div>
</body>

</html>
