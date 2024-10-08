<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    Jobs Name
                    <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div x-show="open" @click.away="open = false"
                class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <a href="{{ route('admin.background-jobs-monitoring.jobs.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Background Jobs
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            // check if role is super admin or admin background jobs
            var isAuthorized = @json(auth()->user()->hasAnyRole(['Super Admin', 'Admin Background Jobs']));
            // AJAX DataTable
            var datatable = $('#dataTable').DataTable({
                serverSide: false,
                scrollX: true,
                stateSave: false,
                ajax: {
                    url: '{{ route('admin.background-jobs-monitoring.processes.index') }}',
                    type: 'GET',
                },
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
                },

                columns: [{
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            var statusLabel = data ? 'Active' : 'Non-Active';
                            var textColorClass = 'text-white';
                            var statusClass = data ? 'bg-green-500' : 'bg-red-600';

                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' +
                                statusClass + ' ' + textColorClass + '">' + statusLabel + '</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
            });

            $('body').on('click', '.btn-edit, .btn-delete', function(e) {
                if (!isAuthorized) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Unauthorized',
                        text: "You don't have permission to perform this action.",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            });

            // Event handler untuk tombol delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();

                // Cek apakah pengguna memiliki autorisasi
                if (!isAuthorized) {
                    Swal.fire({
                        title: 'Unauthorized',
                        text: "You don't have permission to perform this action.",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Jika pengguna berwenang, lanjutkan dengan konfirmasi penghapusan
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
                            @hasanyrole('Super Admin|Admin Background Jobs')
                                <a href="{{ route('admin.background-jobs-monitoring.processes.create') }}"
                                    class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                    style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                    + Add Jobs Module
                                </a>
                            @endhasanyrole
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider rounded-tl-md">
                                        Type</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Status</th>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
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
