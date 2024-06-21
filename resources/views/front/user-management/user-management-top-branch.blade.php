@extends('layouts.homepage')


@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg" style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-4xl font-bold sm:text-4xl" style="color: #152C5B">User Management</h1>
        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdownSelector"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                <option value="{{ route('user-management.top-branch') }}" style="margin-top: 20px;">Top 5 Kanwil Request
                </option>
                <option value="{{ route('user-management.request-by-type') }}" style="margin-top: 20px;">User Management
                    Request</option>
                <option value="{{ route('user-management.monthly-target') }}" style="margin-top: 20px;">Target Realization
                </option>
                <option value="{{ route('user-management.sla-category') }}" style="margin-top: 20px;">SLA Monitoring
                </option>
            </select>
        </div>

        <div class="flex items-center justify-between gap-3 mt-8">
            <div class=" w-1/3 flex">
                <div id="monthFilter" class="mr-2">
                    <select name="month" id="monthSelect" onchange="fetchData()" class="rounded-xl">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="yearFilter">
                    <select name="year" id="yearSelect" onchange="fetchData()" class="rounded-xl">
                        @foreach (range(2020, date('Y')) as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="w-1/3">
                <div class="flex items-end justify-end gap-4">
                    <div id="reqTypeFilter">
                        <select name="req_type" id="reqTypeSelect" onchange="fetchData()" class="rounded-xl w-60">
                            <option value="all">All Requests</option>
                            <option value="Create User">Create User</option>
                            <option value="Change Role">Change Role</option>
                            <option value="Reset User">Reset User</option>
                            <option value="Delete User">Delete User</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <canvas id="branchBarChart" class="mt-6 h-[300px]"></canvas>
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
        let branchBarChart;

        async function fetchData() {
            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;
            const reqType = document.getElementById('reqTypeSelect').value;

            let url = `/api/usman/get-top-kanwil-request-chart?month=${month}&year=${year}&req_type=${reqType}`;

            try {
                const response = await fetch(url);
                const kanwils = await response.json();

                const kanwilNames = kanwils.map(kanwil => kanwil.kanwil_name);
                const requestCounts = kanwils.map(kanwil => kanwil.total_requests);

                const ctx = document.getElementById('branchBarChart').getContext('2d');
                if (branchBarChart) {
                    branchBarChart.destroy();
                }
                branchBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: kanwilNames,
                        datasets: [{
                            label: 'Total Requests',
                            data: requestCounts,
                            backgroundColor: [
                                '#FFC107',
                                '#2ECC71',
                                '#6C97DF',
                                '#EE1515',
                                '#FF8333'
                            ],
                            borderWidth: 1,
                            barThickness: 50,  // Mengatur ketebalan bar
                            maxBarThickness: 50  // Mengatur ketebalan maksimum bar
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Mengubah orientasi chart menjadi horizontal
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Top 5 Kanwil Requests'
                            }

                        }
                    }
                });

            } catch (error) {
                console.error("There was an error fetching the data", error);
            }
        }

        window.onload = fetchData;
    </script>
@endsection
