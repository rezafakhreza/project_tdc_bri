<x-app-layout>
    <x-slot name="title">Admin - Deployments</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-lg bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    Deployments
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
                    <a href="{{ route('admin.deployments.modules.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Modules
                    </a>
                    <a href="{{ route('admin.deployments.server-types.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Server Types
                    </a>
                </div>
            </div>
        </div>

    </x-slot>

    <x-slot name="script">
        <script>
            // AJAX DataTable
            var datatable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('admin.deployments.deployment.index') }}',
                    type: 'GET',
                },
                pageLength: 20, // Set default page length to 20
                orderable: false, // Disable ordering
                columns: [{
                        data: 'id',
                        name: 'id',

                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'module',
                        name: 'module'
                    },
                    {
                        data: 'document_status',
                        name: 'document_status',
                        render: function(data, type, row) {
                            var status = row.document_status;
                            var statusClass = '';
                            var textColorClass = 'text-white'; // Text color set to white

                            if (status == 'In Progress') {
                                statusClass = 'bg-yellow-300';
                            } else if (status == 'Done') {
                                statusClass = 'bg-green-500';
                            } else if (status == 'Not Done') {
                                statusClass = 'bg-red-600';
                            }

                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' +
                                statusClass + ' ' + textColorClass + '">' + status + '</span>';
                        }
                    },
                    {
                        data: 'cm_status',
                        name: 'cm_status'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%',
                    }
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
        <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 ">
            <div class="overflow-hidden shadow sm:rounded-lg">
                <div class="px-4 py-5 bg-white sm:p-6 rounded-lg">
                    <div class="flex justify-between mb-10">
                        <div class="dataTables_filter">
                            <!-- Filter search akan muncul di sini -->
                        </div>

                        <div class="button-container flex gap-4">
                            <a href="{{ route('admin.deployments.deployment.create') }}"
                                class="px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                + Add Deployment
                            </a>
                            <a href="{{ route('deployments.calendar') }}" target="_blank"
                                class="px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                View Chart
                            </a>
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Title</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Module</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Server Type</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Deploy Date</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Document Status</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        CM Status</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Updated At</th>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white dark:text-gray-400 uppercase tracking-wider rounded-tl-md">
                                        Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    </style>


</x-app-layout>
