<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ← Back
            </a>
        </h2>
    </x-slot>

    <div class="py-12 font-poppins">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg">
                <h1 class="mb-10 text-2xl font-medium">Edit CM Status</h1>
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

                <form class="w-full" action="{{ route('admin.deployments.cm-status.update', $cmStatus->id) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mt-4 mb-2 text-sm font-bold tracking-wide text-gray-700"
                                for="cm_status_name">
                                CM Status Name*
                            </label>
                            <input value="{{ old('cm_status_name', $cmStatus->cm_status_name) }}" name="cm_status_name"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="cm_status_name" type="text" placeholder="Nama CM Status" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Nama CM Status wajib diisi.
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-bold tracking-wide text-gray-700"
                                for="colour">
                                Color*
                            </label>
                            <div class="flex items-center">
                                <input value="{{ old('colour', $cmStatus->colour) }}" name="colour"
                                    class="block w-16 h-12 border-none rounded appearance-none focus:outline-none"
                                    id="colour" type="color" required onchange="updateColor(this)">
                                <span id="color-code"
                                    class="block px-4 py-3 ml-4 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded">
                                    {{ old('colour') ? old('colour') : $cmStatus->colour }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 text-right">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                                Add CM Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function updateColor(input) {
            const colorCodeSpan = document.getElementById('color-code');
            colorCodeSpan.innerText = input.value;
        }
    </script>
</x-app-layout>
