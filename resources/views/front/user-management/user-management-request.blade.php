@extends('layouts.homepage')

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-4xl font-bold sm:text-4xl" style="color: #152C5B">User Management</h1>

        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;"> <!-- Menambahkan outline -->
                <option value="{{ route('user-management.request-by-type') }}" style="margin-top: 20px;">User Management
                    Request</option>
                <option value="{{ route('user-management.monthly-target') }}" style="margin-top: 20px;">Target Realization
                </option>
                <option value="{{ route('user-management.sla-category') }}" style="margin-top: 20px;">SLA Monitoring
                </option>
                <option value="{{ route('user-management.top-branch') }}" style="margin-top: 20px;">Top 5 Kanwil Request
                </option>
            </select>
        </div>




        <canvas id="incidentChart" width="400" height="200" class="mt-6"></canvas>

        <div class="flex items-center justify-between mt-10">
            <div class="w-1/3">
                <h2 id="totalRequests" class="text-2xl font-semibold"></h2>
            </div>

            <div class="flex justify-center">
                <div id="monthDiv" style="{{ request('mode') == 'date' ? 'display:inline-block;' : 'display:none;' }}">
                    <select name="month" id="monthSelect" onchange="fetchData()"
                        style="padding-right: 90px; font-size: 20px; margin-right:10px;" class="rounded-lg font-semibold mx-auto">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="year" id="yearSelect" onchange="fetchData()"
                        style="padding-right: 90px; font-size: 20px;" class="rounded-lg font-semibold mx-auto">
                        @foreach (range(2020, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="w-1/3">
                <div class="flex flex-col items-end justify-end gap-4">
                    <div class="flex overflow-hidden border rounded-lg">
                        <button type="button" onclick="updateMode('month', event)" id="monthButton"
                            class="px-4 py-2 text-gray-600 {{ request('mode') == 'month' ? 'bg-darker-blue text-white' : '' }}">Per
                            Month</button>
                        <button type="button" onclick="updateMode('date', event)" id="dateButton"
                            class="px-4 py-2 text-gray-600 {{ request('mode') == 'date' ? 'bg-darker-blue text-white' : '' }}">Per
                            Date</button>
                    </div>
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

        let chart;
        // Use 'month' as the default if 'mode' is not specified
        let currentMode = '{{ request('mode') }}' || 'month';

        // Predefined colors
        const predefinedColors = [{
                background: '#FFC107'
            },
            {
                background: '#2ECC71'
            },
            {
                background: '#152C5B'
            },
            {
                background: '#FF8333'
            },
            {
                background: '#2B4CDE'
            },
            {
                background: '#EE1515'
            },
            {
                background: '#BFBFBF'
            },
            {
                background: '#17A2B8'
            },
            {
                background: '#6C97DF'
            },
            {
                background: '#262628'
            },
            {
                background: '#CCDAFCCC'
            },
            {
                background: '#FF6A88CC'
            },
        ];

        function getColor(index) {
            if (index < predefinedColors.length) {
                return predefinedColors[index];
            } else {
                return generateRandomColor();
            }
        }

        function generateRandomColor() {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return {
                background: `rgba(${r}, ${g}, ${b})`,
            };
        }

        function updateMode(selectedMode, event) {
            event.preventDefault();
            currentMode = selectedMode;

            document.getElementById('monthButton').classList.remove('bg-darker-blue', 'text-white');
            document.getElementById('dateButton').classList.remove('bg-darker-blue', 'text-white');

            if (currentMode === 'date') {
                document.getElementById('monthDiv').style.display = 'inline-block';
                document.getElementById('dateButton').classList.add('bg-darker-blue', 'text-white');
            } else {
                document.getElementById('monthDiv').style.display = 'none';
                document.getElementById('monthButton').classList.add('bg-darker-blue', 'text-white');
            }

            fetchData(); // Call fetchData to retrieve data with the selected mode
        }

        function fetchData() {
            const year = document.getElementById('yearSelect').value;
            const month = document.getElementById('monthSelect').value;
            const mode = currentMode;

            fetch(`/api/usman/get-request-by-type-chart?year=${year}&month=${month}&mode=${mode}`)
                .then(response => response.json())
                .then(data => {
                    const labels = mode === 'month' ? data.months : data.days;
                    const ctx = document.getElementById('incidentChart').getContext('2d');

                    document.getElementById('totalRequests').innerHTML = 'Total Requests : ' + data.totalRequests;

                    const typeKeys = Object.keys(data.incidentCounts)
                        .filter(key => Object.keys(data.incidentCounts[key]).length > 0)
                        .map(key => Object.keys(data.incidentCounts[key]))
                        .flat()
                        .filter((value, index, self) => self.indexOf(value) === index);

                    const datasets = [];

                    typeKeys.forEach((type, index) => {
                        const color = getColor(index);
                        const countsForType = labels.map(label => data.incidentCounts[label][type] || 0);

                        datasets.push({
                            label: `${type}`,
                            data: countsForType,
                            backgroundColor: color.background,
                            borderWidth: 1
                        });
                    });

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    stacked: true
                                },
                                x: {
                                    stacked: true
                                }
                            }
                        }
                    });
                });
        }

        // Set the default mode when the page loads
        updateMode(currentMode, new Event('click'));

        // Event listeners for the 'Per Month' and 'Per Date' buttons
        document.getElementById('monthButton').addEventListener('click', function(event) {
            updateMode('month', event);
        });

        document.getElementById('dateButton').addEventListener('click', function(event) {
            updateMode('date', event);
        });

        fetchData();
    </script>
@endsection
