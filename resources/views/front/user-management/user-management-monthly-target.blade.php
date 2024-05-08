@extends('layouts.homepage')

@section('title')
    <title>Target Realization</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-4xl font-bold sm:text-4xl" style="color: #152C5B">User Management</h1>

        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;"> <!-- Menambahkan outline -->
                <option value="{{ route('user-management.monthly-target') }}" style="margin-top: 20px;">Target Realization
                </option>
                <option value="{{ route('user-management.request-by-type') }}" style="margin-top: 20px;">User Management
                    Request</option>
                <option value="{{ route('user-management.sla-category') }}" style="margin-top: 20px;">SLA Monitoring
                </option>
                <option value="{{ route('user-management.top-branch') }}" style="margin-top: 20px;">Top 5 Kanwil Request
                </option>
            </select>
        </div>
        <canvas id="monthlyDataChart" class="mt-6"></canvas>
        <div class="flex justify-center mt-10">
            <select name="year" id="yearFilter" style="padding-right: 90px; font-size: 20px;"
                class="rounded-lg font-semibold mx-auto">
                @foreach (range(2020, date('Y')) as $year)
                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                        {{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById("chartDropdownSelector").addEventListener("change", function() {
            const selectedURL = this.value;
            if (selectedURL) {
                window.location.href = selectedURL;
            }
        });

        let myChart;

        const ctx = document.getElementById('monthlyDataChart').getContext('2d');

        function updateChart(data) {
            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                            label: 'Target',
                            data: data.targets,
                            borderColor: '#EE1515',
                            borderDash: [5, 5],
                            fill: false
                        },
                        {
                            label: 'Actual',
                            data: data.actuals,
                            borderColor: '#2B4CDE',
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function fetchDataForYear(year) {
            fetch('/api/usman/get-monthly-target-actual?year=' + year)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                });
        }

        document.getElementById('yearFilter').addEventListener('change', function() {
            let selectedYear = this.value;
            fetchDataForYear(selectedYear);
        });

        document.addEventListener('DOMContentLoaded', function() {
            let currentYear = new Date().getFullYear();
            document.getElementById('yearFilter').value = currentYear;
            fetchDataForYear(currentYear);
        });
    </script>
@endsection
