<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <div x-data="{ open: false }" class="relative inline-block text-left font-poppins">
            <div>
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-darker-blue focus:outline-none focus:ring focus:ring-slate-400"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{-- show menu apa sekarang --}}
                    Brisol
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
                    <a href="{{ route('admin.brisol.monthly-target.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Monthly Target
                    </a>

                    <a href="{{ route('admin.brisol.foundation-fam.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Foundation FAM
                    </a>

                    <!-- <a href="{{ route('admin.brisol.foundation-iem.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Foundation IEM
                    </a> -->

                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var datatable = $('#dataTable').DataTable({
                    serverSide: false,
                    stateSave: true,
                    scrollX: true,
                    ajax: {
                        url: '{{ route('admin.brisol.incidents.index') }}',
                        type: 'GET',
                    },
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
                    },

                    columns: [
                        {
                            data: 'inc_id',
                            name: 'inc_id',
                        },
                        {
                            data: 'reported_date',
                            name: 'reported_date',
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'region',
                            name: 'region',
                        },
                        {
                            data: 'detailed_description',
                            name: 'detailed_description',
                        },
                        {
                            data: 'service_ci',
                            name: 'service_ci',
                        },
                        {
                            data: 'ctg_tier1',
                            name: 'ctg_tier1',
                        },
                        {
                            data: 'ctg_tier2',
                            name: 'ctg_tier2',
                        },
                        {
                            data: 'ctg_tier3',
                            name: 'ctg_tier3',
                        },
                        {
                            data: 'reported_source',
                            name: 'reported_source',
                        },
                        {
                            data: 'assigned_group',
                            name: 'assigned_group',
                        },
                        {
                            data: 'assignee',
                            name: 'assignee',
                        },
                        {
                            data: 'priority',
                            name: 'priority',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'slm_status',
                            name: 'slm_status',
                        },
                        {
                            data: 'resolved_date',
                            name: 'resolved_date',
                        },
                    ],
                    // Column definitions for handling the rendering of certain columns
                    columnDefs: [{
                            targets: [4], // index for Detailed Description
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    return `<div class="cursor-pointer modal-trigger" data-content="${data}">${data.length > 30 ? data.substr(0, 30) + '...' : data}</div>`;
                                } else {
                                    return data;
                                }
                            }
                        },
                    ]

                });

                function formatTextWithLineBreaks(text) {
                    return text.replace(/(?:\r\n|\r|\n)/g, '<br>').replace(/  /g, '&nbsp;&nbsp;');
                }

                function showModal(content) {
                    var formattedContent = formatTextWithLineBreaks(content);
                    $('#modal-content').html(formattedContent).css('max-height', '60vh').css('overflow-y', 'auto');
                    $('#modal').removeClass('hidden');
                }

                $('#dataTable').on('click', '.modal-trigger', function() {
                    var content = $(this).attr('data-content');
                    showModal(content);
                });

                $('#modal-close').on('click', function() {
                    $('#modal').addClass('hidden');
                });
            });

            function downloadTemplate() {
                var templateUrl = '{{ asset('TemplateExcel/Template_Brisol.xlsx ') }}';
                var link = document.createElement('a');
                link.href = templateUrl;
                link.download = 'Template_Brisol.xlsx';
                link.click();
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

                            <button onclick="downloadTemplate()"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                Download Template
                            </button>

                            <a href="{{ route('admin.brisol.incidents.create') }}"
                                class="pressed-button px-4 py-2 font-bold text-dark-blue rounded-lg shadow-lg font-poppins bg-white  focus:border-blue-900 focus:shadow-outline-blue"
                                style="outline: 2px solid rgb(34, 31, 96); color: #1f1248;">
                                + Import Data
                            </a>
                            <a href="{{ route('brisol.service-ci') }}" target="_blank"
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
                                    <th style="max-width: 1%"
                                        class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">INC
                                        ID</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Reported Date</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Full Name</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Region</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Detailed Description</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Service CI</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Categorization Tier 1</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Categorization Tier 2</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Categorization Tier 3</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Reported Source</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Assigned Group</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Assignee</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Priority</th>                                    
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Status</th>
                                    <th class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        SLM Status</th>
                                    <th style="max-width: 1%"
                                        class="bg-darker-blue text-white uppercase tracking-wider text-left text-xs">
                                        Last Resolved Date</th>
                                </tr>
                            </thead>

                        </table>
                    </div>

                    <div class="flex items-center space-x-4" style="margin-top: 0.9cm">

                        <form action="{{ route('admin.brisol.incidents.destroyAll') }}" method="POST"
                            id="deleteAllForm">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="px-4 py-2 font-bold text-white rounded-lg shadow-lg font-poppins bg-red-600 focus:border-blue-900 focus:shadow-outline-blue btn-delete-all">
                                Hapus Semua Data
                            </button>
                        </form>

                        <script>
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
        <!-- Modal Structure -->
        <div id="modal"
            class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-600 bg-opacity-50">
            <div class="w-full max-w-lg p-4 bg-white rounded-lg shadow-lg">
                <div id="modal-content" class="text-sm overflow-y-auto max-h-[60vh]">
                    <!-- Dynamic content goes here -->
                </div>
                <button id="modal-close"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Close
                </button>
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
