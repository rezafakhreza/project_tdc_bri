<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 font-poppins">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ← Back
            </a>
        </h2>
    </x-slot>

    <div class="py-12 font-poppins">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-xl">
                <h1 class="mb-10 text-2xl font-medium">Add Data Deployment</h1>
                <form action="{{ route('admin.deployments.deployment.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-16">
                        <div>
                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="block mb-2 text-sm font-bold text-gray-600">Title:</label>
                                <input type="text" id="title" name="title"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    value="{{ old('title') }}" required>
                            </div>

                            <!-- Module ID Dropdown -->
                            <div id="module-dropdowns">
                                <div class="module-dropdown mb-4">
                                    <label for="module_id"
                                        class="block mb-2 text-sm font-bold text-gray-600">Module:</label>
                                    <div class="flex items-center">
                                        <select id="module_id" name="module_id[]"
                                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                            required>
                                            <option value="" disabled selected>-- Pilih Module --</option>
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button"
                                            class="ml-2 p-2 bg-blue-500 rounded-full text-white focus:outline-none"
                                            onclick="addModule()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Server Type Dropdown -->
                            <div id="server-type-dropdowns">
                                <div class="server-type-dropdown mb-4">
                                    <label for="server_type_id"
                                        class="block mb-2 text-sm font-bold text-gray-600">Server
                                        Type:</label>
                                    <div class="flex items-center">
                                        <select id="server_type_id" name="server_type_id[]"
                                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                            required>
                                            <option value="" disabled selected>-- Pilih Server Type (Pilih Module
                                                Terlebih Dahulu)--</option>
                                            <!-- Options will be populated based on the selected module -->
                                        </select>
                                        <button type="button"
                                            class="ml-2 p-2 bg-blue-500 rounded-full text-white focus:outline-none"
                                            onclick="addServerType()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Deploy Date -->
                            <div class="mb-4">
                                <label for="deploy_date" class="block mb-2 text-sm font-bold text-gray-600">Deploy
                                    Date:</label>
                                <input type="date" id="deploy_date" name="deploy_date"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    value="{{ old('deploy_date') }}" required>
                            </div>
                        </div>

                        <div>


                            <!-- Document Status -->
                            <div class="mb-4">
                                <label for="document_status" class="block mb-2 text-sm font-bold text-gray-600">Document
                                    Status:</label>
                                <select id="document_status" name="document_status"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    required>
                                    <option value="" selected disabled>-- Pilih Status Dokumen --</option>
                                    <option value="Not Done">Not Done</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>

                            <!-- Document Description -->
                            <div class="mb-4">
                                <label for="document_description"
                                    class="block mb-2 text-sm font-bold text-gray-600">Document Description:</label>
                                <textarea id="document_description" name="document_description" rows="4"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    required></textarea>
                            </div>

                            <!-- CM Status -->
                            <div class="mb-4">
                                <label for="cm_status" class="block mb-2 text-sm font-bold text-gray-600">CM
                                    Status:</label>
                                <select id="cm_status" name="cm_status"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    required>
                                    <option value="" selected disabled>-- Pilih Status CM --</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Reviewer">Reviewer</option>
                                    <option value="Checker">Checker</option>
                                    <option value="Signer">Signer</option>
                                    <option value="Done Deploy">Done Deploy</option>
                                </select>
                            </div>

                            <!-- CM Description -->
                            <div class="mb-4">
                                <label for="cm_description" class="block mb-2 text-sm font-bold text-gray-600">CM
                                    Description:</label>
                                <textarea id="cm_description" name="cm_description" rows="4"
                                    class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                    required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">Add
                            Deployment</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Markup yang ada sebelumnya -->
    <x-slot name="script">
        <script>
            function addModule() {
                var moduleDropdowns = document.getElementById('module-dropdowns');
                var clone = moduleDropdowns.querySelector('.module-dropdown').cloneNode(true);
                var moduleclone = moduleDropdowns.appendChild(clone);

                // Trigger function to rebuild server type dropdowns when a new module is added
                rebuildServerTypeDropdowns();

                // Add event listener to the newly added module dropdown
                moduleclone.addEventListener('change', function() {
                    rebuildServerTypeDropdowns();
                });
            }

            function addServerType() {
                var serverTypeDropdowns = document.getElementById('server-type-dropdowns');
                var clone = serverTypeDropdowns.querySelector('.server-type-dropdown').cloneNode(true);
                var serverclones = serverTypeDropdowns.appendChild(clone);
            }

            function rebuildServerTypeDropdowns() {
                var moduleDropdowns = document.querySelectorAll('[name="module_id[]"]');
                var selectedModules = Array.from(moduleDropdowns).map(function(moduleDropdown) {
                    return moduleDropdown.value;
                });

                // Remove duplicate module IDs
                var uniqueSelectedModules = Array.from(new Set(selectedModules));

                // Fetch server types for all selected modules
                var promises = uniqueSelectedModules.map(moduleId => {
                    return fetch(`/api/modules/${moduleId}/server-types`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => {
                            return response.json();
                        });
                });

                Promise.all(promises)
                    .then(results => {
                        // Merge server types from all modules
                        var combinedServerTypes = results.reduce((accumulator, currentValue) => {
                            return accumulator.concat(currentValue);
                        }, []);

                        // Update all server type dropdowns
                        var serverTypeSelects = document.querySelectorAll('[name="server_type_id[]"]');
                        serverTypeSelects.forEach(function(serverTypeSelect) {
                            serverTypeSelect.innerHTML = '';

                            combinedServerTypes.forEach(function(serverType) {
                                var option = new Option(serverType.name, serverType.id);
                                serverTypeSelect.appendChild(option);
                            });

                            // Set the selected server type to the one previously selected if it exists
                            var previouslySelectedServerType = serverTypeSelect.dataset.previouslySelected;
                            if (previouslySelectedServerType) {
                                serverTypeSelect.value = previouslySelectedServerType;
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching server types:', error);
                    });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var moduleDropdowns = document.querySelectorAll('[name="module_id[]"]');
                moduleDropdowns.forEach(function(moduleDropdown) {
                    moduleDropdown.addEventListener('change', function() {
                        rebuildServerTypeDropdowns();
                    });
                });
            });
        </script>



    </x-slot>

</x-app-layout>
