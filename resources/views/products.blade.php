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
        <h3>Products</h3>
        <!-- New Product Button -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProductModal">New</button>
    </div>

    <div>
        <form action='/products' method="GET" class="row d-flex mt-3">
            <div class="col-2">
                <select name="search_by" class="form-select" required>
                    <option value="name">Name</option>
                    <option value="quantity">Quantity</option>
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
                <a href="/products" class="btn btn-danger">Cancel</a>
            </div>
            @endif
        </form>
    </div>

    <!-- Custom pagination controls -->
    <div class="d-flex justify-content-between mt-5">
        <div>
            <span>{{ $products->count() }} / {{ $products->total() }}</span>
        </div>
        <div>
            @if ($products->onFirstPage())
            <span>Start</span>
            @else
            <a href="{{ $products->previousPageUrl() }}">Previous</a>
            @endif

            @for ($i = 1; $i <= $products->lastPage(); $i++)
                @if ($i == $products->currentPage())
                <strong>{{ $i }}</strong>
                @else
                <a href="{{ $products->url($i) }}">{{ $i }}</a>
                @endif
                @endfor

                @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">Next</a>
                @else
                <span>End</span>
                @endif
        </div>
    </div>

    <!-- Products table -->
    <table class="table table-bordered mt-1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr data-bs-toggle="modal" data-bs-target="#editProductModal"
                data-id="{{ $product->id }}"
                data-name="{{ $product->name }}"
                data-price="{{ $product->price }}"
                data-quantity="{{ $product->quantity }}"
                data-description="{{ $product->description }}">
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for New Product -->
<div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductModalLabel">New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/products" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
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

<!-- Modal for Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteProductButton" data-bs-toggle="modal" data-bs-target="#deleteProductModal">Delete</button>
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
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteProductForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Are you sure you want to delete this product?
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
                const price = this.getAttribute('data-price');
                const quantity = this.getAttribute('data-quantity');
                const description = this.getAttribute('data-description');

                document.getElementById('editProductForm').action = `/products/${id}`; // Đường dẫn cho PUT request
                document.getElementById('editName').value = name;
                document.getElementById('editPrice').value = price;
                document.getElementById('editQuantity').value = quantity;
                document.getElementById('editDescription').value = description;

                document.getElementById('deleteProductForm').action = `/products/${id}`; // Đường dẫn cho DELETE request
            });
        });
    });
</script>

@endsection