@extends('layouts/layout')

@section('content')
<div class="container p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Orders</h3>
        <a href="/orders/newOrder" class="btn btn-primary">New</a>
    </div>

    <div>
        <form action='/orders' method="GET" class="row d-flex mt-3">
            <div class="col-2">
                <select name="search_by" class="form-select" required>
                    <option value="customer_id">Order Id</option>
                    <option value="user_id">User Id</option>
                    <option value="id">Order Id</option>
                </select>
            </div>
            <div class="col-5">
                <input type="text" name="search" class="form-control" placeholder="Search..." required value="{{ request('search') }}">
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-success">Search</button>
            </div>

            @if(request('search'))
            <div class="col-1">
                <a href="/orders" class="btn btn-danger">Cancel</a>
            </div>
            @endif
        </form>
    </div>

    <!-- Orders table -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order Name</th>
                <th>User ID</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr data-bs-toggle="modal" data-bs-target="#editOrderModal"
                data-id="{{ $order->id }}"
                data-customer-id="{{ $order->customer_id }}"
                data-user-id="{{ $order->user_id }}"
                data-total-amount="{{ $order->total_amount }}"
                data-status="{{ $order->status }}">
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer_id }}
                <td>{{ $order->user_id }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>{{ $order->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Custom pagination controls -->
    <div class="d-flex justify-content-between">
        <div>
            <span>{{ $orders->count() }} / {{ $orders->total() }}</span>
        </div>
        <div>
            @if ($orders->onFirstPage())
            <span>Start</span>
            @else
            <a href="{{ $orders->previousPageUrl() }}">Previous</a>
            @endif

            @for ($i = 1; $i <= $orders->lastPage(); $i++)
                @if ($i == $orders->currentPage())
                <strong>{{ $i }}</strong>
                @else
                <a href="{{ $orders->url($i) }}">{{ $i }}</a>
                @endif
                @endfor

                @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}">Next</a>
                @else
                <span>End</span>
                @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> <span id="modal-order-id"></span></p>
                <p><strong>Customer Name:</strong> <span id="modal-customer-name"></span></p>
                <p><strong>Customer Phone:</strong> <span id="modal-customer-phone"></span></p>
                <p><strong>Customer Address:</strong> <span id="modal-customer-address"></span></p>
                <p><strong>Customer Email:</strong> <span id="modal-customer-email"></span></p>
                <p><strong>Total Amount:</strong> <span id="modal-total-amount"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <hr>
                <h5>Order Items:</h5>
                <ul id="modal-order-items"></ul> <!-- List product -->
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" id="deleteOrderButton" data-bs-toggle="modal" data-bs-target="#deleteOrderModal">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOrderModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteOrderForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Are you sure you want to delete this order?
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderRows = document.querySelectorAll('tbody tr');

        orderRows.forEach(row => {
            row.addEventListener('click', function () {
                const orderId = this.getAttribute('data-id');

                axios.get(`/orders/detail?id=${orderId}`)
                    .then(response => {
                        const data = response.data;

                        document.getElementById('modal-order-id').textContent = data.order.id;
                        document.getElementById('modal-customer-name').textContent = data.customer.name;
                        document.getElementById('modal-customer-phone').textContent = data.customer.phone;
                        document.getElementById('modal-customer-address').textContent = data.customer.address;
                        document.getElementById('modal-customer-email').textContent = data.customer.email;
                        document.getElementById('modal-total-amount').textContent = data.order.total_amount;
                        document.getElementById('modal-status').textContent = data.order.status;

                        const orderItemsList = document.getElementById('modal-order-items');
                        orderItemsList.innerHTML = '';

                        data.order_items.forEach(item => {
                            const listItem = document.createElement('li');
                            listItem.textContent = `Product ID: ${item.product_id}, Quantity: ${item.quantity}, Unit Price: ${item.unit_price}`; // Thay đổi trường theo cấu trúc dữ liệu của bạn
                            orderItemsList.appendChild(listItem);
                        });

                        // Update action of delete form
                        document.getElementById('deleteOrderForm').action = `/orders/${orderId}`;
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            });
        });
    });
</script>



@endsection