@extends('layouts.front')

@section('title')
    <title>User Management Request</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">User Management</h1>
        <div class="flex items-center justify-between gap-3">
            <div class="w-1/3">
            </div>
            <div class="w-1/2 mx-auto text-center">
                <select id="chartDropdownSelector"
                    class="w-full px-4 py-4 text-xl text-white border rounded cursor-pointer bg-dark-blue focus:outline-none focus:border-blue-900 focus:shadow-outline-blue">
                    <option value="{{ route('user-management.top-branch') }}">Top 5 Kanwil Request</option>
                    <option value="{{ route('user-management.request-by-type') }}">User Management Request</option>
                    <option value="{{ route('user-management.monthly-target') }}">Target Realization</option>
                    <option value="{{ route('user-management.sla-category') }}">SLA Monitoring</option>
                </select>
            </div>
            <div class="w-1/3">
                <div class="flex items-end justify-end gap-4">
                    <div id="monthFilter">
                        <select name="month" id="monthSelect" onchange="fetchData()">
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="yearFilter">
                        <select name="year" id="yearSelect" onchange="fetchData()">
                            @foreach (range(2020, date('Y')) as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="reqTypeFilter">
                        <select name="req_type" id="reqTypeSelect" onchange="fetchData()">
                            <option value="all">All Requests</option>
                            <option value="Create User">Create User</option>
                            <option value="Change Role">Change Role</option>
                            <option value="Reset User">Reset User</option>
                            <option value="Delete User">Delete User</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <canvas id="branchPieChart" class="mt-6 w-full max-w-lg h-[300px] mx-auto"></canvas>
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
        let branchPieChart;

        async function fetchData() {
            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;
            const reqType = document.getElementById('reqTypeSelect').value; // Ambil nilai jenis permintaan yang dipilih

            // Ubah URL untuk mengambil data kanwil dengan filter bulan, tahun, dan jenis permintaan
            let url = `/api/usman/get-top-kanwil-request-chart?month=${month}&year=${year}&req_type=${reqType}`;

            try {
                const response = await fetch(url);
                const kanwils = await response.json();

                const kanwilNames = kanwils.map(kanwil => kanwil.kanwil_name);
                const requestCounts = kanwils.map(kanwil => kanwil.total_requests);

                // Perbarui Pie Chart
                const ctx = document.getElementById('branchPieChart').getContext('2d');
                if (branchPieChart) {
                    branchPieChart.destroy(); // Hancurkan grafik yang ada sebelum memperbarui
                }
                branchPieChart = new Chart(ctx, {
                    type: 'pie',
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
                            borderWidth: 1
                        }]
                    }
                });

            } catch (error) {
                console.error("There was an error fetching the data", error);
            }
        }

        // Panggil fungsi saat halaman dimuat
        window.onload = fetchData;
    </script>
@endsection
