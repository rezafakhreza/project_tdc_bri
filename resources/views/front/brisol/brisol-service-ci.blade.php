@extends('layouts.homepage')

@section('title')
    <title>Brisol Service CI</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-4xl font-bold sm:text-4xl" style="color: #152C5B">BRI Solution</h1>
        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                <option value="{{ route('brisol.service-ci') }}" style="margin-top: 20px;">Brisol Service CI</option>
                <option value="{{ route('brisol.slm-status') }}" style="margin-top: 20px;">Brisol SLM Status</option>
                <option value="{{ route('brisol.reported-source') }}" style="margin-top: 20px;">Brisol Reported Source
                </option>
                <option value="{{ route('brisol.monthly-target') }}" style="margin-top: 20px;">Brisol Monthly Target
                </option>
                <option value="{{ route('brisol.service-ci-top-issue') }}" style="margin-top: 20px;">Brisol Service CI
                    Top Issue</option>
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
                        style="padding-right: 90px; font-size: 20px; margin-right:10px;"
                        class="rounded-lg font-semibold mx-auto">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="year" id="yearSelect" onchange="fetchData()"
                        style="padding-right: 90px; font-size: 20px; margin-right:10px;"
                        class="rounded-lg font-semibold mx-auto">
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
        // Dropdown selector change event
        document.getElementById("chartDropdownSelector").addEventListener("change", function() {
            const selectedURL = this.value;
            if (selectedURL) {
                window.location.href = selectedURL;
            }
        });

        // Chart initialization
        let chart;

        // Default mode setup
        let currentMode = '{{ request('mode', 'month') }}';

        // Predefined colors array
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
            }
        ];

        // Function to get color, either predefined or random
        function getColor(index) {
            if (index < predefinedColors.length) {
                return predefinedColors[index];
            } else {
                return generateRandomColor();
            }
        }

        // Function to generate random colors
        function generateRandomColor() {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return {
                background: `rgba(${r}, ${g}, ${b}, 0.2)`
            };
        }

        // Function to update the mode (month/date) and refresh the chart
        function updateMode(selectedMode, event) {
            event.preventDefault();
            currentMode = selectedMode;

            setButtonStyles();
            toggleMonthSelectVisibility();
            submitFormForModeChange();
        }

        // Function to set the styles of the mode buttons
        function setButtonStyles() {
            document.getElementById('monthButton').classList.toggle('bg-darker-blue', currentMode === 'month');
            document.getElementById('monthButton').classList.toggle('text-white', currentMode === 'month');
            document.getElementById('dateButton').classList.toggle('bg-darker-blue', currentMode === 'date');
            document.getElementById('dateButton').classList.toggle('text-white', currentMode === 'date');
        }

        // Function to toggle the visibility of the month selector
        function toggleMonthSelectVisibility() {
            document.getElementById('monthDiv').style.display = currentMode === 'date' ? 'inline-block' : 'none';
        }

        // Function to submit the form for mode change
        function submitFormForModeChange() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = `{{ route('brisol.service-ci') }}?mode=${currentMode}`;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'mode';
            hiddenField.value = currentMode;

            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }

        // Function to fetch data and update the chart
        function fetchData() {
            const year = document.getElementById('yearSelect').value;
            const month = document.getElementById('monthSelect').value;
            const mode = currentMode;

            fetch(`/api/brisol/get-service-ci-chart?year=${year}&month=${month}&mode=${mode}`)
                .then(response => response.json())
                .then(data => {
                    const labels = mode === 'month' ? data.months : data.days;
                    const ctx = document.getElementById('incidentChart').getContext('2d');

                    document.getElementById('totalRequests').innerHTML = `Total Requests: ${data.totalRequests}`;

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

        // Set initial UI based on the current mode and fetch data
        setButtonStyles();
        toggleMonthSelectVisibility();
        fetchData();
    </script>
@endsection
