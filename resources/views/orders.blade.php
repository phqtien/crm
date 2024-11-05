@extends('layouts/layout')

@section('content')
<div class="container p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Orders</h3>
        <a href="/orders/newOrder" class="btn btn-primary">New</a>
    </div>

    <div class="mb-3 w-25">
        <label for="statusFilter" class="form-label">Filter by Status</label>
        <select id="statusFilter" class="form-select">
            <option value="">All</option>
            <option value="new">New</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
        </select>
    </div>

    <!-- Orders table -->
    <table class="table table-striped table-bordered mt-1" id="ordersTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>User Name</th>
                <th>Total Amount</th>
                <th>Total Product</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data from DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> <span id="orderId"></span></p>
                <p><strong>Customer Name:</strong> <span id="customerName"></span></p>
                <p><strong>Customer Phone:</strong> <span id="customerPhone"></span></p>
                <p><strong>Customer Address:</strong> <span id="customerAddress"></span></p>
                <p><strong>Customer Email:</strong> <span id="customerEmail"></span></p>
                <p><strong>Total Amount:</strong> <span id="totalAmount"></span></p>
                <p><strong>Status:</strong> <span id="status"></span></p>
                <p><strong>Created At:</strong> <span id="createdAt"></span></p>

                <h5>Order Items:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="orderItems">
                        <!-- Product rows will be appended here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" id="deleteOrderButton" data-bs-dismiss="modal">Delete</button>
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Close</button>
                    <a id="generatePdfLink" href="#" class="btn btn-primary">PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade modal-second" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOrderModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this order?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteOrderBtn" data-bs-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/orders.js') }}"></script>

@endsection