<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    Background Jobs
                    <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div x-show="open" @click.away="open = false"
                class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <a href="{{ route('admin.background-jobs-monitoring.processes.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Jobs Name
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            // AJAX DataTable
            var datatable = $('#dataTable').DataTable({
                serverSide: false,
                scrollX: true,
                stateSave: false,
                ajax: {
                    url: '{{ route('admin.background-jobs-monitoring.jobs.index') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'process.name',
                        name: 'process.name',
                    },
                    {
                        data: 'data_amount_to_EIM',
                        name: 'data_amount_to_EIM',
                        render: function(data, type, row) {
                            return data.toLocaleString();
                        }
                    },
                    {
                        data: 'data_amount_to_S4GL',
                        name: 'data_amount_to_S4GL',
                        render: function(data, type, row) {
                            return data.toLocaleString();
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'duration_to_EIM',
                        name: 'duration_to_EIM',
                        render: function(data, type, row) {
                            let hours = Math.floor(data / 3600);
                            let minutes = Math.floor((data % 3600) / 60);
                            let seconds = data % 60;

                            let formattedDuration = '';

                            if (hours > 0) formattedDuration += `${hours}h `;
                            if (minutes > 0 || (hours > 0 && seconds === 0)) formattedDuration +=
                                `${minutes}m `;
                            if (seconds > 0 || (minutes === 0 && hours === 0)) formattedDuration +=
                                `${seconds}s`;

                            return formattedDuration;
                        }
                    },
                    {
                        data: 'duration_to_S4GL',
                        name: 'duration_to_S4GL',
                        render: function(data, type, row) {
                            let hours = Math.floor(data / 3600);
                            let minutes = Math.floor((data % 3600) / 60);
                            let seconds = data % 60;

                            let formattedDuration = '';

                            if (hours > 0) formattedDuration += `${hours}h `;
                            if (minutes > 0 || (hours > 0 && seconds === 0)) formattedDuration +=
                                `${minutes}m `;
                            if (seconds > 0 || (minutes === 0 && hours === 0)) formattedDuration +=
                                `${seconds}s`;

                            return formattedDuration;
                        }
                    },
                    {
                        data: 'notes',
                        name: 'notes',
                    },
                    {
                        data: 'execution_date',
                        name: 'execution_date',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
                // order by execution_date first
                order: [
                    [7, 'desc']
                ],

            });

            // sweet alert delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $(this).parents('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="flex justify-between mb-10">
                        <div class="dataTables_filter">
                            <!-- Filter search akan muncul di sini -->
                        </div>
                        <div class="button-container flex gap-4">
                            <a href="{{ route('admin.background-jobs-monitoring.jobs.create') }}"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                + Add Jobs
                            </a>
                            <a href="{{ route('background-jobs-monitoring.daily') }}" target="_blank"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                View Chart
                            </a>
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>

                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Module</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Job Name</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Data Amount to EIM</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Data Amount to S4GL</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Duration To EIM</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Duration To S4GL</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Notes</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase ">
                                        Monitoring Date</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white  uppercase ">
                                        Last Updated</th>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase ">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @if (session('error'))
        <div class="alert alert-danger">
            {!! session('error') !!}
        </div>
    @endif

    <style>
        .table-responsive {
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper {
            margin-top: -80px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left;
            text-align: left;
            margin-bottom: 30px;
        }

        .dataTables_wrapper .dataTables_length {
            display: none;
        }

        .rounded-table {
            border-collapse: separate;
            border-radius: 0.5rem;
            /* Atur radius lengkungan sesuai keinginan */
            overflow: hidden;
            /* Memastikan sudut melengkung tidak terpotong */
        }

        .rounded-table th:first-child {
            border-top-left-radius: 0.5rem;
            /* Atur radius lengkungan pada sudut kiri atas */
        }

        .rounded-table th:last-child {
            border-top-right-radius: 0.5rem;
            /* Atur radius lengkungan pada sudut kanan atas */
        }

        .rounded-table tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
            /* Atur radius lengkungan pada sudut kiri bawah */
        }

        .rounded-table tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
            /* Atur radius lengkungan pada sudut kanan bawah */
        }

        .button-container a {
            position: relative;
            z-index: 1;
        }

        .button-container a {
            position: relative;
            z-index: 1;
        }

        .pressed-button {
            transition: transform 0.1s, box-shadow 0.2s;
            background-color: #ffffff;
            /* Warna latar belakang asli */
        }

        .pressed-button:active {
            transform: translateY(2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            background-color: rgba(0, 0, 0, 0.1);
            /* Warna latar belakang lebih gelap saat ditekan */
        }

        .pressed-button:hover {
            background-color: #f3f4f6;
            /* Warna latar belakang saat dihover */
        }
    </style>

</x-app-layout>
