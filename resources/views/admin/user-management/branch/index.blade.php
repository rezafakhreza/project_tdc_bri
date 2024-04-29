<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    Branch
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
                    <a href="{{ route('admin.user-management.monthly-target.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Monthly Target
                    </a>
                    <a href="{{ route('admin.user-management.incidents.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        User Management
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            var isAuthorized = @json(auth()->user()->hasAnyRole(['Super Admin', 'Admin Usman']));
            // AJAX DataTable
            var datatable = $('#dataTable').DataTable({
                serverSide: false,
                scrollX: true,
                stateSave: false,
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
                        data: 'uker_induk_wilayah_code',
                        name: 'uker_induk_wilayah_code',
                        searchable: true,
                    },
                    {
                        data: 'level_uker',
                        name: 'level_uker',
                        searchable: true,
                    },
                    {
                        data: 'uker_induk_kc',
                        name: 'uker_induk_kc',
                        searchable: true,
                    },
                    {
                        data: 'sbo',
                        name: 'sbo',
                        searchable: true,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    }

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

    <script>
        function downloadTemplate() {
            var templateUrl = '{{ asset('TemplateExcel/Template_Data_Branch.xlsx') }}';
            var link = document.createElement('a');
            link.href = templateUrl;
            link.download = 'Template_Data_Branch.xlsx';
            link.click();
        }
    </script>



    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="flex justify-between mb-10">
                        <div class="dataTables_filter">
                            <!-- Filter search akan muncul di sini -->
                        </div>

                        <div class="button-container flex gap-4">
                            <button onclick="downloadTemplate()"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                Download Template
                            </button>

                            <a href="{{ route('admin.user-management.branch.create') }}"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                + Import Data
                            </a>
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>
                                    <th style="max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Kode Unit Kerja</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Unit Kerja</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Kode Kantor Wilayah</th>
                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Level Unit Kerja</th>

                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white  uppercase tracking-wider">
                                        Uker Induk KC</th>

                                    <th
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        SBO</th>

                                    <th style="max-width: 1%"
                                        class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Action
                                    </th>
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
            margin-top: -20px;

        }

        .dataTables_wrapper .dataTables_filter {
            float: left;
            text-align: left;
            margin-top: -60px;
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
