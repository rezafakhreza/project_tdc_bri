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
            <div class="p-6 bg-white rounded-md">
                <h1 class="mb-10 text-2xl font-medium">Edit Branch</h1>
                <div>
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
                    <form class="w-full" action="{{ route('admin.user-management.branch.update', $branch->branch_code) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-16">
                            <div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="branch_name">
                                        Nama Unit Kerja*
                                    </label>
                                    <input value="{{ old('branch_name') ?? $branch->branch_name }}" name="branch_name" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="branch_name" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>



                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="level_uker">
                                        Level Unit Kerja*
                                    </label>
                                    <!-- <input value="{{ old('level_uker') ?? $branch->level_uker }}" name="level_uker" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="kanwil_name" type="text" placeholder="Nama Kanwil"> -->
                                    <select name="level_uker" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" id="level_uker" required>
                                        <option value="" disabled {{ old('level_uker', $branch->level_uker) ? '' : 'selected'}}>Select Level Uker</option>
                                        <option value="AIW" {{ old('level_uker', $branch->level_uker) === 'AIW' ? 'selected' : ''}}>AIW</option>
                                        <option value="BRI UNIT" {{ old('level_uker', $branch->level_uker) === 'BRI UNIT' ? 'selected' : ''}}>BRI UNIT</option>
                                        <option value="Campus" {{ old('level_uker', $branch->level_uker) === 'Campus' ? 'selected' : ''}}>Campus</option>
                                        <option value="Kanpus" {{ old('level_uker', $branch->level_uker) === 'Kanpus' ? 'selected' : ''}}>Kanpus</option>
                                        <option value="KC" {{ old('level_uker', $branch->level_uker) === 'KC' ? 'selected' : ''}}>KC</option>
                                        <option value="KCP" {{ old('level_uker', $branch->level_uker) === 'KCP' ? 'selected' : ''}}>KCP</option>
                                        <option value="KK" {{ old('level_uker', $branch->level_uker) === 'KK' ? 'selected' : ''}}>KK</option>
                                        <option value="Regional Office" {{ old('level_uker', $branch->level_uker) === 'Regional Office' ? 'selected' : ''}}>Regional Office</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="uker_induk_wilayah_code">
                                        Kode Kantor Wilayah*
                                    </label>
                                    <input value="{{ old('uker_induk_wilayah_code') ?? $branch->uker_induk_wilayah_code }}" name="uker_induk_wilayah_code" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="uker_induk_wilayah_code" type="number" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                    Kode tidak boleh lebih dari 4 digit.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="kanwil_name">
                                        Nama Kantor Wilayah*
                                    </label>
                                    <input value="{{ old('kanwil_name') ?? $branch->kanwil_name }}" name="kanwil_name" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="kanwil_name" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>

                                

                            </div>

                            <div>
                                

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="uker_induk_kc">
                                        Uker Induk KC*
                                    </label>
                                    <input value="{{ old('uker_induk_kc') ?? $branch->uker_induk_kc }}" name="uker_induk_kc" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="uker_induk_kc" type="number" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                    Kode tidak boleh lebih dari 4 digit.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="sbo">
                                        SBO*
                                    </label>
                                    <!-- <input value="{{ old('level_uker') ?? $branch->level_uker }}" name="level_uker" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="kanwil_name" type="text" placeholder="Nama Kanwil"> -->
                                    <select name="sbo" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" id="sbo" required>
                                        <option value="" disabled {{ old('sbo', $branch->sbo) ? '' : 'selected'}}>Select SBO</option>
                                        <option value="SBO" {{ old('sbo', $branch->sbo) === 'SBO' ? 'selected' : ''}}>SBO</option>
                                        <option value="NON SBO" {{ old('sbo', $branch->sbo) === 'NON SBO' ? 'selected' : ''}}>NON SBO</option>

                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-600" for="is_active">
                                        Status*
                                    </label>
                                    <select name="is_active" id="is_active" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" required>
                                        <option value="" disabled>Select Status</option>
                                        <option value="1" {{ $branch->is_active == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $branch->is_active == 0 ? 'selected' : '' }}>Non-Active</option>
                                    </select>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Select the status of the module. Mandatory. -->
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="flex flex-wrap mb-6 -mx-3">
                            <div class="w-full px-3 text-right">
                                <button type="submit" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                                    Update Branch
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>