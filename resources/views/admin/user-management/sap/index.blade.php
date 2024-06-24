<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    User SAP
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
                    <a href="{{ route('admin.user-management.incidents.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        User Management
                    </a>

                    <a href="{{ route('admin.user-management.branch.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Branch
                    </a>

                    <a href="{{ route('admin.user-management.monthly-target.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Monthly Target
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
                    url: '{{ route('admin.user-management.sap.index') }}',
                    type: 'GET',
                },
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
                },

                columns: [{
                        data: 'user_pn',
                        name: 'user_pn',
                        searchable: true,
                    },
                    {
                        data: 'creation_date',
                        name: 'creation_date',
                        searchable: true,
                    },
                    {
                        data: 'last_logon',
                        name: 'last_logon',
                        searchable: true,
                    },
                    {
                        data: 'inactive_month',
                        name: 'inactive_month',
                        searchable: true,
                    },
                    {
                        data: 'inactive_category',
                        name: 'inactive_category',
                        searchable: true,
                    },
                    {
                        data: 'first_name',
                        name: 'first_name',
                        searchable: true,
                    },
                    {
                        data: 'last_name',
                        name: 'last_name',
                        searchable: true,
                    },
                    {
                        data: 'branch_code',
                        name: 'branch_code',
                        searchable: true,
                    },
                    {
                        data: 'department',
                        name: 'department',
                        searchable: true,
                    },
                    {
                        data: 'branch_level',
                        name: 'branch_level',
                        searchable: true,
                    },
                    {
                        data: 'lock_status',
                        name: 'lock_status',
                        searchable: true,
                    },
                    {
                        data: 'user_group',
                        name: 'user_group',
                        searchable: true,
                    },
                    {
                        data: 'div_code',
                        name: 'div_code',
                        searchable: true,
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


        <script>
            function showImportForm() {
                var modal = document.getElementById('importModal');
                modal.classList.remove('hidden');

                // Menambahkan event listener untuk tombol cancel
                document.getElementById('cancelButton').addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }
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

                            @hasanyrole('Super Admin|Admin Usman')
                                <a class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                    style="outline: 2px solid rgb(34, 31, 96); color: #1f1248; cursor: pointer;"
                                    onclick="showImportForm()">
                                    + Import Data
                                </a>
                            @endhasanyrole

                            <div id="importModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                                                        Import Data
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            Please choose a file to import data.
                                                        </p>
                                                        <form id="uploadForm"
                                                            action="{{ route('admin.user-management.sap.store') }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <input type="file" name="file" class=" rounded-lg"
                                                                    required>
                                                                <button type="submit"
                                                                    class="btn-large px-4 py-2 ml-2 text-white rounded-lg bg-darker-blue">Import</button>
                                                            </div>

                                                            <div class="mb-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="true" id="overwrite" name="overwrite">
                                                                <label class="form-check-label" for="overwrite">
                                                                    Replace file if found
                                                                </label>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                            <button id="cancelButton"
                                                class="pressed-button block mx-auto mt-4 px-10 py-2 font-bold text-red-700 rounded-lg shadow-lg font-poppins bg-white focus:border-blue-900 focus:shadow-outline-blue"
                                                style="outline: 2px solid rgb(226, 10, 10); color: red; margin-right:2px">
                                                Cancel
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dataTables_wrapper">
                        <table id="dataTable" class="font-poppins font-medium text-sm rounded-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%; max-width: 1%"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        User PN</th>
                                    <th style="width: 15%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Creation Date</th>
                                    <th style="width: 10%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Last Logon</th>
                                    <th style="width: 15%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Inactive Month</th>
                                    <th style="width: 15%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Inactive Category</th>
                                    <th style="width: 15%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white  uppercase tracking-wider">
                                        First Name</th>
                                    <th style="width: 10%;"
                                        class="px-6 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Last Name</th>
                                    <th style="width: 10%;"
                                        class="px-30 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Branch</th>
                                    <th style="width: 10%;"
                                        class="px-30 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Departement</th>
                                    <th style="width: 10%;"
                                        class="px-30 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Branch Level</th>
                                    <th style="width: 10%;"
                                        class="px-30 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Lock Status</th>
                                    <th style="width: 10%;"
                                        class="px-30 py-3 bg-darker-blue text-left text-xs font-medium text-white uppercase tracking-wider">
                                        User Group</th>
                                    <th style="width: 5%; max-width: 1%"
                                        class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Div Code</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="flex items-center space-x-4" style="margin-top: 0.9cm">

                        <form action="{{ route('admin.user-management.sap.destroyAll') }}" method="POST"
                            id="deleteAllForm">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="px-4 py-2 font-bold text-white rounded-lg shadow-lg font-poppins bg-red-600 focus:border-blue-900 focus:shadow-outline-blue btn-delete-all">
                                Hapus Semua Data
                            </button>
                        </form>

                        <script>
                            var isAuthorized = @json(auth()->user()->hasAnyRole(['Super Admin', 'Admin Usman']));

                            $(document).ready(function() {
                                $('body').on('click', '.btn-delete-all', function(e) {
                                    e.preventDefault();

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
                                            // Submit the form if confirmed
                                            $('#deleteAllForm').submit();
                                        }
                                    });
                                });
                            });
                        </script>

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

        .btn-large {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
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
