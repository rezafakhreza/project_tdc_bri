@extends('layouts.homepage')

@section('title')
    <title>Deployment Chart</title>
@endsection

@section('content')
    <div class="p-8 mx-auto my-8 bg-white shadow-lg max-w-7xl">
        <div class="flex justify-between p-2 mb-4 text-white rounded calendar-filter">
            <div>
                <select id="serverTypeSelect" class="p-2 mx-2 border rounded w-28 bg-darker-blue">
                    @foreach ($serverTypes as $serverType)
                        <option value="{{ $serverType->id }}">{{ $serverType->name }}</option>
                    @endforeach
                </select>

                <!-- Year Select -->
                <select id="yearSelect" class="p-2 mx-2 text-white border rounded cursor-pointer w-28 bg-darker-blue">
                    <!-- <option value="2023">2023</option> -->
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>

            <div class="relative">
                <button
                    class="inline-flex px-4 py-2 text-white rounded bg-darker-blue focus:outline-none focus:ring-2 focus:ring-gray-200 dropdown-btn">
                    Chart Server
                    <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div
                    class="absolute right-0 z-10 hidden w-48 py-2 mt-2 bg-white border border-gray-300 rounded shadow dropdown-menu">
                    <a href="{{ route('deployments.calendar') }}"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Calendar</a>
                    <a href="{{ route('deployments.index') }}"
                        class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Chart Module</a>
                </div>
            </div>
        </div>


        <canvas id="myChart" width="400" height="150" class="mt-6"></canvas>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dropdown toggle
            const dropdownBtn = document.querySelector('.dropdown-btn');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            dropdownBtn.addEventListener('click', function() {
                dropdownMenu.classList.toggle('hidden');
            });
        });
    </script>
    <script>
        var myChart;

        // Predefined colors array
        const predefinedColors = [
            '#FFC107', '#2ECC71', '#152C5B', '#FF8333', '#2B4CDE',
            '#EE1515', '#BFBFBF', '#17A2B8', '#6C97DF', '#262628',
            '#CCDAFCCC', '#FF6A88CC'
        ];

        function getColor(index) {
            if (index < predefinedColors.length) {
                return predefinedColors[index];
            } else {
                return randomColor();
            }
        }

        function fetchData() {
            var selectedServer = document.getElementById('serverTypeSelect').value;
            var selectedYear = document.getElementById('yearSelect').value;
            fetch(`/api/deployments/chart-data-server?server_type_id=${selectedServer}&year=${selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    if (myChart) {
                        myChart.destroy();
                    }
                    var ctx = document.getElementById('myChart').getContext('2d');
                    renderChart(ctx, data);
                });
        }

        function renderChart(ctx, data) {
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var datasets = {};
            var dataPoints = Array(12).fill(0);

            data.forEach(function(record) {
                if (!datasets[record.module]) {
                    datasets[record.module] = Array(12).fill(0);
                }
                datasets[record.module][record.month - 1] = record.count;
            });

            var chartDatasets = [];
            var stackCounter = 0;

            for (var module in datasets) {
                var color = getColor(stackCounter);
                chartDatasets.push({
                    label: module,
                    data: datasets[module],
                    borderColor: color,
                    borderWidth: 1,
                    fill: true,
                    backgroundColor: color,
                    stack: 'Stack ' + stackCounter
                });
                stackCounter++;
            }

            let max_value = 0;

            for (let module in datasets) {
                let moduleMax = Math.max(...datasets[module]);
                max_value = Math.max(max_value, moduleMax);
            }

            max_value = Math.max(max_value, 10);

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: chartDatasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: max_value,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function randomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchData();
            var serverTypeSelect = document.getElementById('serverTypeSelect');
            var yearSelect = document.getElementById('yearSelect');
            serverTypeSelect.addEventListener('change', fetchData);
            yearSelect.addEventListener('change', fetchData);
        });
    </script>
@endsection
