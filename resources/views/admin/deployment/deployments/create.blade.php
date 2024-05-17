<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/multiselect.css') }}">
</head>

<body>
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
                                    <label for="title" class="block mb-2 text-sm font-bold text-gray-600">Title*</label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                    <div class="mt-2 text-sm text-gray-500">
                                        Contoh: 20240107 - NCM - FAM - Enhancement Asset Verification. Maksimal 200 karakter.
                                    </div>
                                </div>

                                <!-- Deploy Date -->
                                <div class="mb-4">
                                    <label for="deploy_date" class="block mb-2 text-sm font-bold text-gray-600">Deploy Date*</label>
                                    <input type="date" id="deploy_date" name="deploy_date" value="{{ old('deploy_date') }}" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                </div>

                                <!-- Module ID Dropdown -->
                                <div class="mb-4">
                                    <label for="module_id" class="block mb-2 text-sm font-bold text-gray-600">Module*</label>
                                    <select id="module_id" name="module_id[]" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" multiple="multiple" required>
                                        <!-- <option value="" disabled selected>-- Pilih Module --</option> -->
                                        @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ (is_array(old('module_id')) && in_array($module->id, old('module_id'))) ? 'selected' : '' }}>
                                            {{ $module->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2 text-sm text-gray-500">
                                        Bisa memilih lebih dari 1 Module.
                                    </div>
                                </div>

                                <!-- Server Type ID Dropdown -->
                                <div class="mb-4">
                                    <label for="server_type_id" class="block mb-2 text-sm font-bold text-gray-600">Server Type*</label>
                                    <select id="server_type_id" name="server_type_id[]" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" multiple="multiple" required>
                                        @foreach ($serverTypes as $serverType)
                                        <option value="{{ $serverType->id }}" {{ (is_array(old('server_type_id')) && in_array($serverType->id, old('server_type_id'))) ? 'selected' : '' }}>
                                            {{ $serverType->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2 text-sm text-gray-500">
                                        Bisa memilih lebih dari 1 Server Type.
                                    </div>
                                </div>




                            </div>

                            <div>
                                <!-- Document Status -->
                                <div class="mb-4">
                                    <label for="document_status" class="block mb-2 text-sm font-bold text-gray-600">Document Status*</label>
                                    <select id="document_status" name="document_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                        <option value="" selected disabled>-- Pilih Status Dokumen --</option>
                                        <option value="Not Done" {{ old('document_status') == 'Not Done' ? 'selected' : '' }}>Not Done</option>
                                        <option value="In Progress" {{ old('document_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Done" {{ old('document_status') == 'Done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                </div>

                                <!-- Document Description -->
                                <div class="mb-4">
                                    <label for="document_description" class="block mb-2 text-sm font-bold text-gray-600">Document Description*</label>
                                    <textarea id="document_description" name="document_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>{{ old('document_description') }}</textarea>
                                </div>

                                <!-- CM Status -->
                                <div class="mb-4">
                                    <label for="cm_status" class="block mb-2 text-sm font-bold text-gray-600">CM Status*</label>
                                    <select id="cm_status" name="cm_status" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>
                                        <option value="" selected disabled>-- Pilih Status CM --</option>
                                        <option value="Draft" {{ old('cm_status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="Reviewer" {{ old('cm_status') == 'Reviewer' ? 'selected' : '' }}>Reviewer</option>
                                        <option value="Checker" {{ old('cm_status') == 'Checker' ? 'selected' : '' }}>Checker</option>
                                        <option value="Signer" {{ old('cm_status') == 'Signer' ? 'selected' : '' }}>Signer</option>
                                        <option value="Done Deploy" {{ old('cm_status') == 'Done Deploy' ? 'selected' : '' }}>Done Deploy</option>
                                    </select>
                                </div>

                                <!-- CM Description -->
                                <div class="mb-4">
                                    <label for="cm_description" class="block mb-2 text-sm font-bold text-gray-600">CM Description*</label>
                                    <textarea id="cm_description" name="cm_description" rows="4" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" required>{{ old('cm_description') }}</textarea>
                                </div>



                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">Add Deployment</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </x-app-layout>

    <script src="{{ asset('JS/multiselect.js') }}" type="text/javascript"></script>
    <script>
        new MultiSelectTag('module_id', {
            rounded: true, // default true
            shadow: false, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#000000',
                borderColor: '#444444',
                bgColor: '#eeeeee',
            },
            onChange: function(values) {
                var modules = values;
                if (modules.length > 0) {
                    $.ajax({
                        url: "{{ route('admin.deployments.deployment.jabar') }}",
                        type: 'POST',
                        data: {
                            module_id: values,
                        },
                    })
                }
            }
        })

        new MultiSelectTag('server_type_id', {
            rounded: true, // default true
            shadow: false, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#000000',
                borderColor: '#444444',
                bgColor: '#eeeeee',
            },
            onChange: function(values) {
                var server_type_id = values;
                if (server_type_id.length > 0) {
                    $.ajax({
                        url: "{{ route('admin.deployments.deployment.jabar') }}",
                        type: 'POST',
                        data: {
                            server_type_id: values,
                        },
                    })
                }
            }
        })
    </script>

</body>

</html>