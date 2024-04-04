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
            <div class="p-6 bg-white rounded-lg">
                <h1 class="mb-10 text-2xl font-medium">Add Data</h1>

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

                <!-- Checkbox untuk opsi "Overwrite" di luar alert -->


                <!-- Form untuk tombol "Import" di dalam alert -->
                <form action="{{ route('admin.user-management.branch.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class=" rounded-lg" required>
                        <button type="submit"
                            class="px-4 py-2 ml-2 text-white rounded-lg bg-darker-blue">Import</button>
                    </div>

                </form>

                <form action="{{ route('admin.user-management.branch.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
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
</x-app-layout>
