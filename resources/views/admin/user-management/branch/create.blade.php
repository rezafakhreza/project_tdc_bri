<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 font-poppins">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ← Back
            </a>
        </h2>
    </x-slot>



    <!-- Overlay untuk loading animation -->
    <div id="loadingOverlay" class="fixed inset-0 z-50 flex items-center justify-center hidden">

        <div class="bg-white p-4 rounded-lg shadow z-10">
            <div class="flex items-center">
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6 mr-2"></div>
                <span class="text-gray-700">Uploading...</span>
            </div>
        </div>
        <div style="background-color: rgba(0, 0, 0, 0.5);  position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
        </div>
    </div>

    @if ($errors->any())
    <div class="py-6 font-poppins mx-auto max-w-7xl lg:px-8" role="alert">
        <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">
            Ada kesalahan!
        </div>
        <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
            <p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            </p>
        </div>
    </div>
    @endif

    <div class="py-12 font-poppins">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-6 bg-white rounded-lg">
            <h1 class="mb-6 text-2xl font-medium">Add Data</h1>

            <form class="w-full" action="{{ route('admin.user-management.branch.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-2 gap-16">
                    <div>
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="branch_code" class="block mb-2 text-sm font-bold text-gray-600">Kode Unit Kerja*</label>
                            <input type="text" id="branch_code" name="branch_code" value="{{ old('branch_code') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Kode tidak boleh lebih dari 4 digit.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="branch_name" class="block mb-2 text-sm font-bold text-gray-600">Nama Unit Kerja*</label>
                            <input type="text" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                        </div>

                        <div class="mb-4">
                            <label for="uker_induk_wilayah_code" class="block mb-2 text-sm font-bold text-gray-600">Kode Kantor Wilayah*</label>
                            <input type="text" id="uker_induk_wilayah_code" name="uker_induk_wilayah_code" value="{{ old('uker_induk_wilayah_code') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Kode tidak boleh lebih dari 4 digit.
                            </div>
                        </div>
                    </div>
                    <div>

                        <!-- Document Status -->
                        <div class="mb-4">
                            <label for="level_uker" class="block mb-2 text-sm font-bold text-gray-600">Level Unit Kerja*</label>
                            <select id="level_uker" name="level_uker" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="" disabled selected>-- Pilih Level Uker --</option>
                                <option value="AIW" {{ old('level_uker') == 'AIW' ? 'selected' : '' }}>AIW</option>
                                <option value="BRI UNIT" {{ old('level_uker') == 'BRI UNIT' ? 'selected' : '' }}>BRI UNIT</option>
                                <option value="Campus" {{ old('level_uker') == 'Campus' ? 'selected' : '' }}>Campus</option>
                                <option value="Kanpus" {{ old('level_uker') == 'Kanpus' ? 'selected' : '' }}>Kanpus</option>
                                <option value="KC" {{ old('level_uker') == 'KC' ? 'selected' : '' }}>KC</option>
                                <option value="KCP" {{ old('level_uker') == 'KCP' ? 'selected' : '' }}>KCP</option>
                                <option value="KK" {{ old('level_uker') == 'KK' ? 'selected' : '' }}>KK</option>
                                <option value="Regional Office" {{ old('status') == 'Regional Office' ? 'selected' : '' }}>Regional Office</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="uker_induk_kc" class="block mb-2 text-sm font-bold text-gray-600">Uker Induk KC*</label>
                            <input type="text" id="uker_induk_kc" name="uker_induk_kc" value="{{ old('uker_induk_kc') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Kode tidak boleh lebih dari 4 digit.
                            </div>
                        </div>

                        <!-- CM Status -->
                        <div class="mb-4">
                            <label for="sbo" class="block mb-2 text-sm font-bold text-gray-600">SBO*</label>
                            <select id="sbo" name="sbo" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="" disabled selected>-- Select SBO --</option>
                                <option value="SBO" {{ old('sbo') == 'SBO' ? 'selected' : '' }}>SBO</option>
                                <option value="NON SBO" {{ old('sbo') == 'NON SBO' ? 'selected' : '' }}>NON SBO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap mb-6 -mx-3">
                    <div class="w-full px-3 text-right">
                        <input type="hidden" name="input_method" value="manual">
                        <button type="submit" name="input_method" value="manual" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                            Add Branch
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        document.getElementById("uploadForm").addEventListener("submit", function() {
            showLoadingOverlay();
        });

        function showLoadingOverlay() {
            document.getElementById("loadingOverlay").classList.remove("hidden");
        }

        function hideLoadingOverlay() {
            document.getElementById("loadingOverlay").classList.add("hidden");
        }
    </script>

    <style>
        .loader {
            border-top-color: #3498db;
            animation: spinner 1.5s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</x-app-layout>