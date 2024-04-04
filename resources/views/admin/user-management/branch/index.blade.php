<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400" id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    Branch
                    <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div x-show="open" @click.away="open = false" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <a href="{{ route('admin.user-management.monthly-target.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Monthly Target
                    </a>
                    <a href="{{ route('admin.user-management.incidents.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        User Management
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
                stateSave: true,
                ajax: {
                    url: '{{ route('admin.user-management.branch.index') }}',
                    type: 'GET',
                },
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
                },

                columns: [{
                        data: 'branch_code',
                        name: 'branch_code',
                        searchable: true,
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        searchable: true,
                    },
                    {
                        data: 'kanwil_code',
                        name: 'kanwil_code',
                        searchable: true,
                    },
                    {
                        data: 'kanwil_name',
                        name: 'kanwil_name',
                        searchable: true,
                    },

                ],
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
                            <a href="{{ route('admin.user-management.branch.create') }}" class="px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue" style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                + Import Data
                            </a>
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Kode Unit Kerja</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                    Unit Kerja</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Kode Kantor Wilayah</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Kantor Wilayah</th>
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