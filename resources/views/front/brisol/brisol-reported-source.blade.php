@extends('layouts.homepage')

@section('title')
    <title>Brisol Reported Source</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Brisol Reported Source</h1>

        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                <option value="{{ route('brisol.reported-source') }}" style="margin-top: 20px;">Brisol Reported Source
                </option>
                <option value="{{ route('brisol.service-ci') }}" style="margin-top: 20px;">Brisol Service CI</option>
                <option value="{{ route('brisol.slm-status') }}" style="margin-top: 20px;">Brisol SLM Status</option>
                <option value="{{ route('brisol.monthly-target') }}" style="margin-top: 20px;">Brisol Monthly Target
                </option>
                <option value="{{ route('brisol.service-ci-top-issue') }}" style="margin-top: 20px;">Brisol Service CI
                    Top Issue</option>
            </select>
        </div>

        <canvas id="reportedSourceChart" width="400" height="200" class="mt-6"></canvas>

        <div class="flex items-center justify-between mt-12">
            <div class="w-1/3">
            </div>



            <div class="w-1/3">
                <div class="flex flex-col items-end justify-end gap-4">
                    <div class="mb-5">
                        <label for="yearFilter" class="mr-2"></label>
                        <select id="yearFilter" onchange="loadChartData() style="padding-right: 90px; font-size: 20px;
                            margin-right:10px;" class="rounded-lg font-semibold mx-auto">
                            @foreach (range(2020, date('Y')) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')
    <script>
        document.getElementById("chartDropdownSelector").addEventListener("change", function() {
            window.location.href = this.value;
        });

        let currentChart;

        // Predefined colors array
        const predefinedColors = ["#6fe7dd", "#3490de", "#6639a6", "#521262", "#ffbf00", "#ff6f61", 
        "#2ec4b6", "#ff9f1c", "#9b51e0", "#f2c94c", "#e76f51","#264653"
        ];

        function getColor(index) {
            if (index < predefinedColors.length) {
                return predefinedColors[index];
            } else {
                // Generate random color with transparency
                return '#' + Math.floor(Math.random() * 16777215).toString(16);
            }
        }

        function loadChartData(year = document.getElementById('yearFilter').value) {
            fetch(`/api/brisol/get-reported-source-chart?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('reportedSourceChart').getContext('2d');
                    if (currentChart) {
                        currentChart.destroy();
                    }
                    const uniqueSources = [...new Set(data.months.flatMap(month => Object.keys(data.data[month])))];

                    const datasets = uniqueSources.map((source, index) => ({
                        label: source,
                        data: data.months.map(month => data.data[month][source] || 0),
                        backgroundColor: getColor(index),
                    }));

                    currentChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.months,
                            datasets: datasets,
                        },
                        options: {
                            scales: {
                                y: {
                                    stacked: true,
                                    beginAtZero: true
                                },
                                x: {
                                    stacked: true
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            loadChartData();

            document.getElementById('yearFilter').addEventListener('change', function() {
                loadChartData(this.value);
            });
        });
    </script>
@endsection
