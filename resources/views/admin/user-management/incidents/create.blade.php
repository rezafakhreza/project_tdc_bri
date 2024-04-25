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
        <div
            style="background-color: rgba(0, 0, 0, 0.5);  position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
        </div>
    </div>

    <div class="py-12 font-poppins">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg">
                <h1 class="mb-10 text-2xl font-medium">Add Data User Management</h1>

                @if ($errors->any())
                    <div class="mb-5" role="alert">
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

                <!-- Form untuk tombol "Import" di dalam alert -->
                <form id="uploadForm" action="{{ route('admin.user-management.incidents.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class="rounded-lg" required>
                        <button id="importButton" type="submit"
                            class="px-4 py-2 ml-2 text-white rounded-lg bg-darker-blue">Import</button>
                    </div>

                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" value="true" id="overwrite" name="overwrite">
                        <label class="form-check-label" for="overwrite">
                            Overwrite existing file if found
                        </label>
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
