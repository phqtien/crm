@extends('layouts/layout')

@section('content')
<div class="container p-3">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center">
        <h3>Customers</h3>
        <!-- New Customer Button -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCustomerModal">New</button>
    </div>

    <div>
        <form action='/customers' method="GET" class="row d-flex mt-3">
            <div class="col-2">
                <select name="search_by" class="form-select" required>
                    <option value="name">Name</option>
                    <option value="phone">Phone</option>
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
                <a href="/customers" class="btn btn-danger">Cancel</a>
            </div>
            @endif
        </form>

    </div>

    <!-- Custom pagination controls -->
    <div class="d-flex justify-content-between mt-5">
        <div>
            <span>{{ $customers->count() }} / {{ $customers->total() }}</span>
        </div>
        <div>
            @if ($customers->onFirstPage())
            <span>Start</span>
            @else
            <a href="{{ $customers->previousPageUrl() }}">Previous</a>
            @endif

            @for ($i = 1; $i <= $customers->lastPage(); $i++)
                @if ($i == $customers->currentPage())
                <strong>{{ $i }}</strong>
                @else
                <a href="{{ $customers->url($i) }}">{{ $i }}</a>
                @endif
                @endfor

                @if ($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}">Next</a>
                @else
                <span>End</span>
                @endif
        </div>
    </div>

    <!-- Customers table -->
    <table class="table table-bordered mt-1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr data-bs-toggle="modal" data-bs-target="#editCustomerModal"
                data-id="{{ $customer->id }}"
                data-name="{{ $customer->name }}"
                data-phone="{{ $customer->phone }}"
                data-address="{{ $customer->address }}"
                data-email="{{ $customer->email }}">
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for New Customer -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCustomerModalLabel">New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/customers" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit Customer -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Cutomer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Adress</label>
                        <input type="text" class="form-control" id="editAddress" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteCustomerButton" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal">Delete</button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCustomerModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteCustomerForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Are you sure you want to delete this customer?
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('tr[data-bs-toggle="modal"]').forEach(function(row) {
            row.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const phone = this.getAttribute('data-phone');
                const address = this.getAttribute('data-address');
                const email = this.getAttribute('data-email');

                document.getElementById('editCustomerForm').action = `/customers/${id}`;
                document.getElementById('editName').value = name;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editAddress').value = address;
                document.getElementById('editEmail').value = email;

                document.getElementById('deleteCustomerForm').action = `/customers/${id}`;
            });
        });
    });
</script>

@endsection