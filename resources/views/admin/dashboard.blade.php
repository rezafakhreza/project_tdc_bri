<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <span class="text-blue-800">Selamat Datang,</span>
            <span class="text-yellow-300">{{ Auth::user()->name }} !!</span>
        </h2>
    </x-slot>

    <div class="min-h-screen relative bg-opacity-75">

        <div class="absolute inset-0 z-10 bg-cover bg-no-repeat bg-center"
            style="background-image: url('/img/cover.jpg');">
        </div>

        <div class="py-12 mx-auto max-w-7xl relative">
            halo
        </div>

    </div>


</x-app-layout>
