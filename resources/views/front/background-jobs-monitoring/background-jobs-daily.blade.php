@extends('layouts.homepage')

@section('title')
    <title>Background Jobs - Daily Monitoring</title>
@endsection

@section('content')
    <div class="p-10 mx-auto my-10 rounded-lg shadow-xl" style="background-color: white; border: 1px solid #d9d9d9;">
        <div class="flex flex-col mb-5">
            <div class="flex justify-between">
                <!-- Dropdown navigasi -->
                <select id="navigationDropdown"
                    class="w-56 text-white rounded-lg cursor-pointer focus:outline-none focus:border-blue-900 focus:shadow-outline-blue bg-darker-blue">
                    <option value="{{ route('background-jobs-monitoring.daily') }}">Daily</option>
                    <option value="{{ route('background-jobs-monitoring.data-amount') }}">Chart - Data Amount</option>
                    <option value="{{ route('background-jobs-monitoring.duration') }}">Chart - Duration</option>
                </select>
                <!-- Container untuk filter dan input tanggal -->
                <div class="flex items-center">
                    <!-- Dropdown untuk Tanggal Awal -->
                    <input type="date" id="start-date-selector"
                        class="px-8 py-2 ml-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <!-- Dropdown untuk Tanggal Akhir -->
                    <input type="date" id="end-date-selector"
                        class="px-8 py-2 ml-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <!-- Button untuk apply filter -->
                    <button id="apply-filter-button"
                        class="px-6 py-2 ml-2 text-white rounded-md bg-darker-blue focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">Filter</button>

                </div>
            </div>
        </div>




        <div id="heatmap-container-type1" class="mt-10"></div>

        <div class="flex justify-center">
            <div class="flex items-center mx-2 mb-10">
                <span class="w-8 h-4 mr-3 rounded-lg" style="background-color: #2FB489"></span> Normal Run
            </div>
            <div class="flex items-center mx-2 mb-10">
                <span class="w-8 h-4 mr-3 rounded-lg" style="background-color: #9BD95A"></span> Rerun Background Job
            </div>
            <div class="flex items-center mx-2 mb-10">
                <span class="w-8 h-4 mr-3 rounded-lg" style="background-color: #FFBB46"></span> Manual Run Background Job
            </div>
            <div class="flex items-center mx-2 mb-10">
                <span class="w-8 h-4 mr-3 rounded-lg" style="background-color: #FE504F"></span> Pending
            </div>
        </div>

        <div id="heatmap-container-type2"></div>

    </div>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        document.getElementById('navigationDropdown').addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        })
        document.addEventListener('DOMContentLoaded', function() {
            // Set default start date to the beginning of the current year
            const currentDate = new Date();
            const startOfYear = new Date(currentDate.getFullYear(), 0, 1);
            const startDateSelector = document.getElementById('start-date-selector');
            startDateSelector.value = startOfYear.toISOString().slice(0, 10);

            // Set default end date to the current month and year
            const endOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const endDateSelector = document.getElementById('end-date-selector');
            endDateSelector.value = endOfMonth.toISOString().slice(0, 10);

            applyFilter();

            document.getElementById('apply-filter-button').addEventListener('click', function() {
                applyFilter();
            });
        });

        function applyFilter() {
            const currentDate = new Date();

            // Set start date to the beginning of the current year (1 Januari tahun ini)
            const startOfYear = new Date(currentDate.getFullYear(), 0, 1);
            const startDateSelector = document.getElementById('start-date-selector');
            startDateSelector.value = startOfYear.toISOString().slice(0, 10);

            // Set end date to today's date
            const endDate = currentDate;
            const endDateSelector = document.getElementById('end-date-selector');
            endDateSelector.value = endDate.toISOString().slice(0, 10);

            // Get the newly set start and end dates
            const startDate = startDateSelector.value;
            const dates = getDatesInRange(new Date(startDate), endDate);

            // Fetch data based on the updated date range
            fetchData(startDate, endDate.toISOString().slice(0, 10), dates);
        }



        function getDatesInRange(startDate, endDate) {
            const dates = [];
            let currentDate = startDate;

            while (currentDate <= endDate) {
                dates.push(currentDate.toISOString().slice(0, 10)); // Format: YYYY-MM-DD
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }

        function fetchData(startDate, endDate, dates) {
            fetch(`/api/bjm/get-background-jobs-daily?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    initializeChart('heatmap-container-type1', data.type1.processes || {}, dates, 'Product');
                    initializeChart('heatmap-container-type2', data.type2.processes || {}, dates, 'Non-Product');
                })
                .catch(error => console.error('Error fetching heatmap data:', error));
        }



        function initializeChart(containerId, data, dates, title) {
            let mappedData = Object.entries(data).flatMap(([date, processes]) =>
                Object.entries(processes).map(([process, {
                    status,
                    notes
                }]) => ({
                    date: date,
                    process: process,
                    status: status,
                    notes: notes
                }))
            );

            let categories = mappedData.map(item => item.process).filter((value, index, self) => self.indexOf(value) ===
                index);

            let statusMap = {
                'Normal Run': 0,
                'Rerun Background Job': 1,
                'Manual Run Background Job': 2,
                'Pending': 3
            };


            let seriesData = [];

            dates.forEach(date => {
                categories.forEach(process => {
                    const foundData = mappedData.find(d => d.date === date && d.process === process);
                    let y = categories.indexOf(process);
                    let x = dates.indexOf(date);
                    if (foundData) {
                        let value = statusMap.hasOwnProperty(foundData.status) ? statusMap[foundData
                            .status] : null;
                        seriesData.push({
                            x,
                            y,
                            value,
                            notes: foundData.notes
                        });
                    } else {
                        seriesData.push({
                            x,
                            y,
                            value: null,
                            notes: null
                        });
                    }
                });
            });


            Highcharts.chart(containerId, {
                chart: {
                    type: 'heatmap',
                    marginTop: 40,
                    marginBottom: 80,
                    plotBorderWidth: 1
                },
                title: {
                    text: title
                },
                xAxis: {
                    categories: dates.map(date => {
                        const [year, month, day] = date.split('-');
                        return `${day} ${Highcharts.getOptions().lang.shortMonths[parseInt(month) - 1]} ${year}`;
                    }),
                    title: {
                        text: 'Tanggal'
                    },
                    labels: {
                        formatter: function() {
                            return this.value;
                        }
                    }
                },
                yAxis: {
                    categories: categories,
                    title: null
                },
                colorAxis: {
                    min: -1,
                    max: Object.keys(statusMap).length - 1,
                    stops: [
                        [-1, '#000000'],
                        [0, '#3060cf'],
                        [0.33, '#2ecf71'],
                        [0.66, '#ffe243'],
                        [1, '#ff5050']
                    ]
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    formatter: function() {
                        const statusMap = {
                            '-1': 'Unexpected/Error',
                            '0': 'Normal Run',
                            '1': 'Rerun Background Job',
                            '2': 'Manual Run Background Job',
                            '3': 'Pending',
                        };
                        const status = statusMap[String(this.point.value)] || '-';
                        const notes = this.point.notes ? this.point.notes : '-';
                        const date = this.series.xAxis.categories[this.point.x];
                        const process = this.series.yAxis.categories[this.point.y];
                        return `<b>Date:</b> ${date}<br/>
                <b>Process: ${process}</b><br/>
                <b>Status:</b> ${status}<br/>
                <b>Notes:</b> ${notes}`;
                    },
                },
                series: [{
                    name: 'Job Status',
                    borderWidth: 1,
                    borderColor: '#000000',
                    data: seriesData,
                    dataLabels: {
                        enabled: false,
                    }
                }]
            });

        }
    </script>
@endsection
