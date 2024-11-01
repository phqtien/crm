@extends('layouts/layout')

@section('content')
<div class="container p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Notifications</h3>
    </div>

    <!-- Custom pagination controls -->
    <div class="d-flex justify-content-between mt-5">
        <div>
            <span>{{ $notifications->count() }} / {{ $notifications->total() }}</span>
        </div>
        <div>
            @if ($notifications->onFirstPage())
            <span>Start</span>
            @else
            <a href="{{ $notifications->previousPageUrl() }}">Previous</a>
            @endif

            @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                @if ($i == $notifications->currentPage())
                <strong>{{ $i }}</strong>
                @else
                <a href="{{ $notifications->url($i) }}">{{ $i }}</a>
                @endif
                @endfor

                @if ($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}">Next</a>
                @else
                <span>End</span>
                @endif
        </div>
    </div>

    <!-- Notifications table -->
    <table class="table table-bordered mt-1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
            <tr data-bs-toggle="modal" data-bs-target="#deleteNotificationModal"
                data-id="{{ $notification->id }}">
                <td>{{ $notification->id }}</td>
                <td>{{ $notification->message }}</td>
                <td>{{ $notification->created_at }}</td>
            </tr>
            @endforeach
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
            <form id="deleteNotificationForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Are you sure you want to delete this notification?
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
                document.getElementById('deleteNotificationForm').action = `/notifications/${id}`;
            });
        });
    });
</script>

@endsection