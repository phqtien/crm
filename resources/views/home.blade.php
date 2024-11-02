@extends('layouts/layout')

@section('title', 'Home')

@section('content')

<div class="p-3">
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <h3 class="mb-4">Statistics</h3>

    <div class="row g-3 mb-3">
        <div class="col-auto">
            <select id="statistics-select" class="form-select" required>
                <option value="month">Monthly</option>
                <option value="quarter">Quarterly</option>
                <option value="year">Yearly</option>
            </select>
        </div>
        <div class="col-auto" id="year-input">
            <input type="number" id="time-input" class="form-control" placeholder="Enter Year (YYYY)" value="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
        </div>
        <div class="col-auto">
            <button id="generate-report" class="btn btn-primary">Generate Report</button>
        </div>
    </div>

    <div id="chart-container" class="vh-50">
        <canvas id="revenue-chart" style="height: 75vh;"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/home.js') }}"></script>

@endsection