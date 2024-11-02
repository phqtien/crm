document.addEventListener('DOMContentLoaded', function () {
    const statisticsSelect = document.getElementById('statistics-select');
    const yearInput = document.getElementById('year-input');
    const timeInput = document.getElementById('time-input');
    const generateButton = document.getElementById('generate-report');
    const chartContainer = document.getElementById('chart-container');
    let revenueChart;

    statisticsSelect.addEventListener('change', function () {
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
            .then(function (response) {
                const report = response.data.report;
                displayChart(report, statistics);
            })
            .catch(function (error) {
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