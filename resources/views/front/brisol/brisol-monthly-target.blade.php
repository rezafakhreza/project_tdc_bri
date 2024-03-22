@extends('layouts.homepage')

@section('title')
    <title>Brisol Monthly Target</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Brisol Monthly Target</h1>

        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                <option value="{{ route('brisol.monthly-target') }}" style="margin-top: 20px;">Brisol Monthly Target
                </option>
                <option value="{{ route('brisol.slm-status') }}" style="margin-top: 20px;">Brisol SLM Status</option>
                <option value="{{ route('brisol.service-ci') }}" style="margin-top: 20px;">Brisol Service CI</option>
                <option value="{{ route('brisol.reported-source') }}" style="margin-top: 20px;">Brisol Reported Source
                </option>

                <option value="{{ route('brisol.service-ci-top-issue') }}" style="margin-top: 20px;">Brisol Service CI
                    Top Issue</option>
            </select>
        </div>

        <canvas id="monthlyDataChart" class="mt-6"></canvas>

        <div class="flex items-center justify-between gap-3 mt-4">
            <div class="w-1/3">
            </div>

            <div class="w-1/3">
                <div class="flex items-end justify-end gap-4">
                    <select name="year" id="yearFilter" style="padding-right: 90px; font-size: 20px; margin-right:10px;"
                        class="rounded-lg font-semibold mx-auto">
                        @foreach (range(2020, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
            fetch('/api/brisol/get-monthly-target-actual?year=' + year)
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
