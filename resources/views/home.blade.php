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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statisticsSelect = document.getElementById('statistics-select');
        const yearInput = document.getElementById('year-input');
        const timeInput = document.getElementById('time-input');
        const generateButton = document.getElementById('generate-report');
        const chartContainer = document.getElementById('chart-container');
        let revenueChart;

        statisticsSelect.addEventListener('change', function() {
            if (this.value === 'month' || this.value === 'quarter') {
                yearInput.style.display = 'block';
                timeInput.setAttribute('required', 'required');
            } else {
                yearInput.style.display = 'none';
                timeInput.removeAttribute('required');
            }
        });

        generateReport();

        generateButton.addEventListener('click', generateReport);

        function generateReport() {
            const statistics = statisticsSelect.value;
            const time = timeInput.value;

            axios.get('/statistify', {
                    params: {
                        statistics: statistics,
                        time: time
                    }
                })
                .then(function(response) {
                    const report = response.data.report;
                    displayChart(report, statistics);
                })
                .catch(function(error) {
                    console.error(error);
                    chartContainer.innerHTML = "<p class='text-danger'>Error fetching data.</p>";
                });
        };

        function displayChart(report, statistics) {
            if (!report || report.length === 0) {
                chartContainer.innerHTML = "<p>No data for this statistic.</p>";
                return;
            }

            const labels = report.map(item => item.month || item.quarter || item.year);
            const data = report.map(item => item.total_revenue);

            const chartData = {
                labels: labels,
                datasets: [{
                    label: 'Total Revenue',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            if (revenueChart) {
                revenueChart.destroy();
            }

            const ctx = document.getElementById('revenue-chart').getContext('2d');
            revenueChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: `Revenue Report (${statistics})`
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: statistics === 'month' ? 'Month' : (statistics === 'quarter' ? 'Quarter' : 'Year')
                            }
                        }
                    }
                }
            });
        }
    });
</script>

@endsection