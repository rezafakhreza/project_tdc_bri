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
                    <form class="w-full" action="{{ route('admin.brisol.foundation-iem.update', $iem->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-16">
                            <div>
                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="level_uker">
                                        Product Categorization Tier 1*
                                    </label>
                                    <select name="prd_tier1" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" id="prd_tier1" required>
                                        <option value="" disabled {{ old('prd_tier1', $iem->prd_tier1) ? '' : 'selected'}}>Product Categorization Tier 1</option>
                                        <option value="SAP" {{ old('prd_tier1', $iem->prd_tier1) === 'SAP' ? 'selected' : ''}}>SAP</option>

                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="prd_tier2">
                                        Product Categorization Tier 2*
                                    </label>
                                    <select name="prd_tier2" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" id="prd_tier2" required>
                                        <option value="" disabled {{ old('prd_tier2', $iem->prd_tier2) ? '' : 'selected'}}>Product Categorization Tier 2</option>
                                        <option value="AA" {{ old('prd_tier2', $iem->prd_tier2) === 'AA' ? 'selected' : ''}}>AA</option>
                                        <option value="AP" {{ old('prd_tier2', $iem->prd_tier2) === 'AP' ? 'selected' : ''}}>AP</option>
                                        <option value="BC" {{ old('prd_tier2', $iem->prd_tier2) === 'BC' ? 'selected' : ''}}>BC</option>
                                        <option value="GR" {{ old('prd_tier2', $iem->prd_tier2) === 'GR' ? 'selected' : ''}}>GR</option>
                                        <option value="MM" {{ old('prd_tier2', $iem->prd_tier2) === 'MM' ? 'selected' : ''}}>MM</option>
                                        <option value="RE-FX" {{ old('prd_tier2', $iem->prd_tier2) === 'RE-FX' ? 'selected' : ''}}>RE-FX</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="prd_tier3">
                                        Product Categorization Tier 3*
                                    </label>
                                    <select name="prd_tier3" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white" id="prd_tier3" required>
                                        <option value="" disabled {{ old('prd_tier3', $iem->prd_tier3) ? '' : 'selected'}}>Product Categorization Tier 3</option>
                                        <option value="Asset Movement" {{ old('prd_tier3', $iem->prd_tier3) === 'Asset Movement' ? 'selected' : ''}}>Asset Movement</option>
                                        <option value="Asset Procurement & DIstribution" {{ old('prd_tier3', $iem->prd_tier3) === 'Asset Procurement & DIstribution' ? 'selected' : ''}}>Asset Procurement & DIstribution</option>
                                        <option value="End of Periodic Processing" {{ old('prd_tier3', $iem->prd_tier3) === 'End of Periodic Processing' ? 'selected' : ''}}>End of Periodic Processing</option>
                                        <option value="Group Reporting Processing" {{ old('prd_tier3', $iem->prd_tier3) === 'Group Reporting Processing' ? 'selected' : ''}}>Group Reporting Processing</option>
                                        <option value="Master Data" {{ old('prd_tier3', $iem->prd_tier3) === 'Master Data' ? 'selected' : ''}}>Master Data</option>
                                        <option value="PSAK-73" {{ old('prd_tier3', $iem->prd_tier3) === 'PSAK-73' ? 'selected' : ''}}>PSAK-73</option>
                                        <option value="User Access" {{ old('prd_tier3', $iem->prd_tier3) === 'User Access' ? 'selected' : ''}}>User Access</option>

                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="op_tier1">
                                    Operational Categorization Tier 1*
                                    </label>
                                    <input value="{{ old('op_tier1') ?? $iem->op_tier1 }}" name="op_tier1" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="op_tier1" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>

                                <div class="mb-4">

                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="op_tier2">
                                    Operational Categorization Tier 2*
                                    </label>
                                    <input value="{{ old('op_tier2') ?? $iem->op_tier2 }}" name="op_tier2" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="op_tier2" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="op_tier3">
                                    Operational Categorization Tier 3*
                                    </label>
                                    <input value="{{ old('op_tier3') ?? $iem->op_tier3 }}" name="op_tier3" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="op_tier3" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase" for="resolution_category">
                                    Resolution Categorization*
                                    </label>
                                    <input value="{{ old('resolution_category') ?? $iem->resolution_category }}" name="resolution_category" class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500" id="resolution_category" type="text" placeholder="">
                                    <div class="mt-2 text-sm text-gray-500">
                                        <!-- Nama modul deployment. Contoh: Module 1, Module 2, dsb. -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap mb-6 -mx-3">
                            <div class="w-full px-3 text-right">
                                <button type="submit" class="px-4 py-2 font-bold text-white rounded shadow-lg bg-darker-blue">
                                    Update Foundation IEM
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>