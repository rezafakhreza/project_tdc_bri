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


                            <div class="flex gap-4 mb-4">
                                <div class="flex-1">
                                    <label for="jmodul" class="block mb-2 text-sm font-bold text-gray-600">Jumlah
                                        Modul</label>
                                    <input type="number" id="jmodul" name="jmodul"
                                        class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                        value="" required>
                                </div>
                                <div class="flex-1">
                                    <label for="jserver" class="block mb-2 text-sm font-bold text-gray-600">Jumlah
                                        Server Type</label>
                                    <input type="number" id="jserver" name="jserver"
                                        class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                        value="" required>
                                </div>
                                <!-- <div class="mb-4 col-span-1"></div> Menghapus label kosong -->
                                <div class="flex-1 flex items-end justify-end">
                                    <button onclick="createArray()"
                                        class="block w-full px-4 py-3 leading-tight bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">Tambahkan</button>
                                </div>
                            </div>

                            <script>
                                // Objek untuk menyimpan pilihan server type untuk setiap modul
                                var selectedServerTypes = {};

                                function createArray() {
                                    var jmodulValue = parseInt(document.getElementById("jmodul").value);
                                    var jserverValue = parseInt(document.getElementById("jserver").value);

                                    // Pastikan jmodul dan jserver tidak kosong
                                    if (isNaN(jmodulValue) || isNaN(jserverValue)) {
                                        alert("Mohon isi jumlah modul dan jumlah server type dengan angka.");
                                        return;
                                    }

                                    // Membuat array berdasarkan nilai input jumlah modul dan jumlah server type
                                    var modulArray = [];
                                    modulArray.push(jmodulValue);
                                    modulArray.push(jserverValue);

                                    // Menambahkan elemen-elemen dropdown Module sesuai dengan jumlah modul
                                    var moduleDropdownContainer = document.getElementById("module-dropdowns");
                                    moduleDropdownContainer.innerHTML = ""; // Menghapus semua elemen sebelumnya

                                    for (var i = 0; i < jmodulValue; i++) {
                                        var moduleDropdown = document.createElement("div");
                                        moduleDropdown.classList.add("module-dropdown", "mb-4");
                                        moduleDropdown.innerHTML = `
            <label for="module_id_${i}" class="block mb-2 text-sm font-bold text-gray-600">Module ${i + 1}:</label>
            <div class="flex items-center">
                <select id="module_id_${i}" name="module_id[]" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                    <option value="" disabled selected>-- Pilih Module --</option>
                    <!-- Di sini Anda dapat menambahkan kode untuk menampilkan opsi modul sesuai kebutuhan Anda -->
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                    @endforeach
                </select>
            </div>
        `;
                                        moduleDropdownContainer.appendChild(moduleDropdown);

                                        // Tambahkan event listener untuk setiap dropdown module
                                        var moduleDropdownSelect = moduleDropdown.querySelector(#module_id_$ {
                                            i
                                        });
                                        moduleDropdownSelect.addEventListener('change', function(event) {
                                            updateServerTypeDropdowns(event.target);
                                        });
                                    }

                                    // Menambahkan elemen-elemen dropdown Server Type sesuai dengan jumlah server type
                                    var serverTypeDropdownContainer = document.getElementById("server-type-dropdowns");
                                    serverTypeDropdownContainer.innerHTML = ""; // Menghapus semua elemen sebelumnya

                                    for (var j = 0; j < jserverValue; j++) {
                                        var serverTypeDropdown = document.createElement("div");
                                        serverTypeDropdown.classList.add("server-type-dropdown", "mb-4");
                                        serverTypeDropdown.innerHTML = `
            <label for="server_type_id_${j}" class="block mb-2 text-sm font-bold text-gray-600">Server Type ${j + 1}:</label>
            <div class="flex items-center">
                <select id="server_type_id_${j}" name="server_type_id[]" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                    <option value="" disabled selected>-- Pilih Server Type (Pilih Module Terlebih Dahulu)--</option>
                    <!-- Options will be populated based on the selected module -->
                </select>
            </div>
        `;
                                        serverTypeDropdownContainer.appendChild(serverTypeDropdown);
                                    }
                                }
                                // Fungsi untuk memperbarui dropdown Server Type saat module dipilih
                                // Inisialisasi objek untuk menyimpan opsi server type dari setiap modul yang dipilih
                                var selectedServerTypes = {};

                                // Inisialisasi objek untuk menyimpan opsi server type dari setiap modul yang dipilih
                                var selectedServerTypes = {};

                                // Fungsi untuk memperbarui dropdown Server Type saat module dipilih
                                function updateServerTypeDropdowns(selectedModuleDropdown) {
                                    var moduleId = selectedModuleDropdown.value;
                                    var serverTypeDropdowns = document.querySelectorAll('[name="server_type_id[]"]');
                                    var serverTypeDropdownId = selectedModuleDropdown.id.replace("module_id_",
                                    ""); // Mendapatkan index dropdown modul

                                    // Mengecek apakah module dipilih
                                    if (moduleId) {
                                        // Fetch server types untuk module yang dipilih
                                        fetch(/api/modules / $ {
                                                moduleId
                                            }
                                            /server-types, {
                                            method: 'GET',
                                            headers: {
                                                'Content-Type': 'application/json',
                                            },
                                        })
                                    .then(response => response.json())
                                        .then(serverTypes => {
                                            // Simpan pilihan server type untuk modul yang dipilih
                                            selectedServerTypes[serverTypeDropdownId] = serverTypes;

                                            // Gabungkan opsi server type dari semua modul yang dipilih
                                            var combinedServerTypes = [];
                                            Object.values(selectedServerTypes).forEach(types => {
                                                combinedServerTypes = combinedServerTypes.concat(types);
                                            });

                                            // Tambahkan opsi baru ke semua dropdown Server Type
                                            serverTypeDropdowns.forEach(serverTypeDropdown => {
                                                // Kosongkan dropdown Server Type
                                                serverTypeDropdown.innerHTML = "";
                                                combinedServerTypes.forEach(serverType => {
                                                    var option = document.createElement('option');
                                                    option.value = serverType.id;
                                                    option.textContent = serverType.name;
                                                    serverTypeDropdown.appendChild(option);
                                                });
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error fetching server types:', error);
                                        });
                                } else {
                                    // Kosongkan dropdown Server Type jika module tidak dipilih
                                    serverTypeDropdowns.forEach(serverTypeDropdown => {
                                        serverTypeDropdown.innerHTML = "";
                                        serverTypeDropdown.disabled = true; // Menonaktifkan dropdown Server Type
                                        var defaultOption = document.createElement('option');
                                        defaultOption.value = "";
                                        defaultOption.textContent = "-- Pilih Module Terlebih Dahulu --";
                                        defaultOption.disabled = true;
                                        defaultOption.selected = true;
                                        serverTypeDropdown.appendChild(defaultOption);
                                    });

                                    // Hapus pilihan server type untuk modul yang dipilih
                                    delete selectedServerTypes[serverTypeDropdownId];
                                }
                                }
                            </script>

                            <!-- Module ID Dropdown -->
                            <div id="module-dropdowns">
                                <!-- Ini akan diisi dengan dropdown Module setelah tombol "Tambahkan" ditekan -->
                            </div>

                            <!-- Server Type Dropdown -->
                            <div id="server-type-dropdowns">
                                <!-- Ini akan diisi dengan dropdown Server Type setelah tombol "Tambahkan" ditekan -->
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
                        return fetch(/api/modules / $ {
                                moduleId
                            }
                            /server-types, {
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
                    var serverTypeSelects = document.querySelectorAll('[name="server_type_id[]"]'); //ini array
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
                var moduleDropdowns = document.querySelectorAll('[name="module_id[]"]'); //ini array diubah
                moduleDropdowns.forEach(function(moduleDropdown) {
                    moduleDropdown.addEventListener('change', function() {
                        rebuildServerTypeDropdowns();
                    });
                });
            });
        </script>



    </x-slot>

</x-app-layout>
