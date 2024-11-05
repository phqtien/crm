@extends('layouts/layout')

@section('content')
<div class="container p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Notifications</h3>
    </div>

    <!-- Notifications table -->
    <table class="table table-striped table-bordered" id="notificationsTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data from DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade" id="deleteNotificationModal" tabindex="-1" aria-labelledby="deleteNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteNotificationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this notification?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" id="confirmDeleteNotificationBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/notifications.js') }}"></script>
@endsection