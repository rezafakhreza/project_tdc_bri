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
        <h1 class="mb-10 text-2xl font-medium">Add Process</h1>
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
        <form class="w-full" action="{{ route('admin.background-jobs-monitoring.processes.store') }}" method="post" enctype="multipart/form-data">
          @csrf

        <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
            <div class="w-full">
                <label class="block mb-2 text-sm font-bold tracking-wide text-gray-600" for="process_type">
                    Module Type*
                </label>
                <select name="type"
                        class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                        id="process_type" required>
                    <option value="" disabled selected>Select Process Type</option>
                    <option value="Product">Product</option>
                    <option value="Non-Product">Non-Product</option>
                </select>
                <div class="mt-2 text-sm text-gray-500">
                    Select the type of module. Mandatory.
                </div>
            </div>
        </div>
          <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
            <div class="w-full">
              <label class="block mb-2 text-sm font-bold tracking-wide text-gray-600" for="process_name">
                Module Name*
              </label>
              <input value="{{ old('name') }}" name="name"
                     class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                     id="process_name" type="text" placeholder="Nama Proses" required>
              <div class="mt-2 text-sm text-gray-500">
                Nama Modul Background. Contoh: INBOUND - A001 (Indonesia), OUTBOUND - Master Data, dsb. Wajib diisi. Maksimal 255 karakter.
              </div>
            </div>
          </div>
            <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                <div class="w-full">
                    <label class="block mb-2 text-sm font-bold tracking-wide text-gray-600" for="is_active">
                        Status*
                    </label>
                    <select name="is_active" id="is_active"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white"
                            required>
                        <option value="1">Active</option> <!-- Note the value change to 1 -->
                        <option value="0">Non-Active</option> <!-- Note the value change to 0 -->
                    </select>
                    <div class="mt-2 text-sm text-gray-500">
                        Select the status of the module. Mandatory.
                    </div>
                </div>
            </div>




          <div class="flex flex-wrap mb-6 -mx-3">
            <div class="w-full px-3 text-right">
              <button type="submit"
                      class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                Add Module
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
