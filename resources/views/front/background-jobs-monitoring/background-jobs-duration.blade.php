@extends('layouts.homepage')

@section('title')
    <title>Background Jobs - Duration</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-lg " style="background-color: white; border: 1px solid #d9d9d9;">
        <h1 class="mb-4 text-2xl font-semibold sm:text-3xl">Background Jobs - Duration</h1>
        <div class="flex w-1/3 text-center" style="margin-left: 67%; margin-top: -60px; margin-bottom: 50px;">
            <select id="chartDropdown"
                class="w-full px-4 py-4 text-xl text-dark-blue rounded-lg font-semibold cursor-pointer bg-white focus:border-blue-900 focus:shadow-outline-blue shadow-lg"
                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                @foreach ($allChartData as $processName => $chartData)
                    <option value="{{ $processName }}">{{ $processName }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center justify-between mt-15" style="margin-bottom: 1cm">
            <div class="w-1/3">
                <select id="navigationDropdown"
                    class="w-56 text-white rounded-lg cursor-pointer focus:outline-none focus:border-blue-900 focus:shadow-outline-blue bg-darker-blue">
                    <option value="{{ route('background-jobs-monitoring.duration') }}">Chart - Duration</option>
                    <option value="{{ route('background-jobs-monitoring.data-amount') }}">Chart - Data Amount</option>
                    <option value="{{ route('background-jobs-monitoring.daily') }}">Daily</option>
                </select>
            </div>

            <div class="flex justify-center">
                @if ($mode == 'date')
                    <div class="mr-4">
                        <form action="{{ route('background-jobs-monitoring.duration') }}" method="GET">
                            <input type="hidden" name="mode" value="date">
                            <select name="month" onchange="this.form.submit()"
                                class="form-select rounded-lg font-semibold mx-auto"
                                style="padding-right: 90px; font-size: 20px; margin-left: 670px;">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $chosenMonth == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif

                @if ($mode == 'month')
                    <div class="mr-4">
                        <select name="year" onchange="this.form.submit()"
                            class="form-select rounded-lg font-semibold mx-auto"
                            style="padding-right: 90px; font-size: 20px; margin-left: 730px;">
                            @foreach (range(date('Y') - 5, date('Y')) as $year)
                                <option value="{{ $year }}" {{ $chosenYear == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="w-1/3">
                <form method="GET" action="{{ route('background-jobs-monitoring.duration') }}"
                    class="flex flex-col items-end justify-end gap-4">
                    <div class="flex mr-4 overflow-hidden border rounded-lg">
                        <button type="button" onclick="setMode('month', event)"
                            class="px-4 py-2 {{ $mode == 'month' ? 'bg-darker-blue text-white' : 'text-gray-600' }}">Month</button>
                        <button type="button" onclick="setMode('date', event)"
                            class="px-4 py-2 {{ $mode == 'date' ? 'bg-darker-blue text-white' : 'text-gray-600' }}">Day</button>
                    </div>
                </form>
            </div>
        </div>

        <h3 class="mt-4 text-xl font-semibold text-center sm:text-2xl" id="chartTitle">Loading...</h3>
        <canvas id="singleChart" class="mt-4"></canvas>
    </div>

@endsection

@section('script')
    <script>
        document.getElementById('navigationDropdown').addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });

        function setMode(selectedMode, event) {
            event.preventDefault();

            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('background-jobs-monitoring.duration') }}";

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'mode';
            hiddenField.value = selectedMode;

            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const chartsData = @json($allChartData);
            const ctx = document.getElementById('singleChart').getContext('2d');
            let chart;

            const chartDropdown = document.getElementById('chartDropdown');

            function renderChart(processName) {
                const data = chartsData[processName];

                if (chart) {
                    chart.destroy();
                }

                document.getElementById('chartTitle').innerHTML = processName;

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Duration to EIM (in seconds)',
                            data: data.durationsEIM,
                            borderColor: '#2B4CDE',
                            fill: false
                        }, {
                            label: 'Duration to S4GL (in seconds)',
                            data: data.durationsS4GL,
                            borderColor: '#FFC107',
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                    }
                });
            }

            chartDropdown.addEventListener('change', function() {
                renderChart(this.value);
                localStorage.setItem('selectedChart', this.value); // Simpan pilihan chart
            });

            const savedChart = localStorage.getItem('selectedChart');
            if (savedChart) {
                chartDropdown.value = savedChart;
                renderChart(savedChart);
            } else {
                renderChart(Object.keys(chartsData)[0]);
            }
        });
    </script>
@endsection
