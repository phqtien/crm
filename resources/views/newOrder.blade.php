@extends('layouts.layout')

@section('title', 'New Order')

@section('content')
<div class="container p-3">
    <h2>Create New Order</h2>

    <form id="search-form" onsubmit="return false;">
        <div class="mb-3">
            <label for="phone" class="form-label">Search customer by phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter customer phone" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="searchCustomer()">Search</button>
    </form>

    <div id="error-message" class="mt-4 alert alert-danger d-none"></div>
    <div id="customer-info" class="mt-4"></div>

    <div id="product-table" class="border-top pt-4 mt-4 d-none">
        <h3>Add Products to Order</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Number</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="product-body">
                <tr>
                    <td class="align-content-center">1</td>
                    <td>
                        <input type="text" class="form-control" placeholder="Enter product name" onkeyup="debounceFetchProductDetails(this)">
                    </td>
                    <td class="align-content-center product-price"></td>
                    <td>
                        <input type="number" id="number" class="w-50 form-control" min="0" oninput="calculateTotal(this)">
                    </td>
                    <td class="align-content-center product-total"></td>
                </tr>
            </tbody>
        </table>
        <div class="border-bottom mb-4 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add</button>
            <h3 class="mt-4">Order Total: <span id="order-total">0.00</span></h3>
        </div>

        <button id="create-order-btn" class="btn btn-success mt-4" onclick="createOrder()">Create Order</button>
    </div>
</div>

<script src="{{ asset('js/newOrder.js') }}"></script>

@endsection