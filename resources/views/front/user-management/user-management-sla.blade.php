@extends('layouts.homepage')

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg " style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-4xl font-bold sm:text-4xl" style="color: #152C5B">User Management</h1>

        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;"> <!-- Menambahkan outline -->
                <option value="{{ route('user-management.sla-category') }}" style="margin-top: 20px;">SLA Monitoring
                </option>
                <option value="{{ route('user-management.request-by-type') }}" style="margin-top: 20px;">User Management
                    Request</option>
                <option value="{{ route('user-management.monthly-target') }}" style="margin-top: 20px;">Target Realization
                </option>
                <option value="{{ route('user-management.top-branch') }}" style="margin-top: 20px;">Top 5 Kanwil Request
                </option>
            </select>
        </div>

        <canvas id="slaChart" class="mt-6"></canvas>
        <div class="flex justify-end mt-16">
            <div class="flex items-end justify-end gap-4">
                <select id="month" class="form-control rounded-lg font-semibold mx-auto"
                    style="display: inline-block; width: auto; padding-right: 90px; font-size: 20px; margin-right:10px;"
                    onchange="updateChartData()">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1, date('Y'))) }}</option>
                    @endfor
                </select>
                <select id="year" class="form-control rounded-lg font-semibold mx-auto"
                    style="display: inline-block; width: auto; padding-right: 90px; font-size: 20px; margin-right:10px;"
                    onchange="updateChartData()">
                    @for ($i = date('Y'); $i >= 2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="flex rounded-lg items-center justify-start"
            style="margin-top: -60px; outline: 2px solid rgb(34, 31, 96); width: 20%">
            <div class="flex w-full flex-col p-2 text-dark-blue rounded-lg bg-white border">
                <span class="text-lg font-bold">Total Incident: <span id="totalIncident">Loading...</span></span>
                <span class="text-lg">Done: <span id="doneIncidents">Loading...</span></span>
                <span class="text-lg">Pending: <span id="pendingIncidents">Loading...</span></span>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById("chartDropdownSelector").addEventListener("change", function() {
            const selectedURL = this.value;
            if (selectedURL) {
                window.location.href = selectedURL;
            }
        });

        let slaChart;

        function updateChartData() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            fetch(`/api/usman/get-sla-category-chart?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalIncident').textContent = data.totals.totalIncidents;
                    document.getElementById('doneIncidents').textContent = data.totals.doneIncidents;
                    document.getElementById('pendingIncidents').textContent = data.totals.pendingIncidents;

                    const slaData = data.slaData;
                    const labels = Object.keys(slaData);
                    const meetSLAData = [];
                    const overSLAData = [];

                    labels.forEach(date => {
                        const day = new Date(date).getDate();
                        meetSLAData.push(slaData[date]['Meet SLA']);
                        overSLAData.push(slaData[date]['Over SLA']);
                    });

                    if (slaChart) {
                        slaChart.destroy();
                    }

                    const ctx = document.getElementById('slaChart').getContext('2d');
                    slaChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels.map(date => new Date(date).getDate()),
                            datasets: [{
                                label: 'Meet SLA',
                                data: meetSLAData,
                                borderColor: '#FFC107',
                                fill: false
                            }, {
                                label: 'Over SLA',
                                data: overSLAData,
                                borderColor: '#2ECC71',
                                fill: false
                            }]
                        },
                    });
                });
        }

        updateChartData();
    </script>
@endsection
