<body style="background-image: url('/img/cover.jpg'); background-size:cover;">
    <div>
        <!-- <nav class="flex justify-between p-5 bg-white font-poppins" id="navbar"> -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- logo -->
                        <div class="flex items-center shrink-0">
                            <a href="{{ route('admin.dashboard') }}" class="text-3xl font-semibold font-poppins">
                                <span class="text-blue-600">TDC</span>Dashboard.
                            </a>
                        </div>



                        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <div class="flex justify-between h-16">
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <x-nav-link href="{{ route('deployments.calendar') }}" :active="request()->routeIs('deployments.calendar')">
                                        {{ __('Deployments') }}
                                    </x-nav-link>

                                    <x-nav-link href="{{ route('background-jobs-monitoring.daily') }}"
                                        :active="request()->routeIs('background-jobs-monitoring.daily')">
                                        {{ __('Background Jobs') }}
                                    </x-nav-link>

                                    <x-nav-link href="{{ route('user-management.request-by-type') }}" :active="request()->routeIs('user-management.request-by-type')">
                                        {{ __('User Management') }}
                                    </x-nav-link>

                                    <x-nav-link href="{{ route('brisol.service-ci') }}" :active="request()->routeIs('brisol.service-ci')">
                                        {{ __('Brisol') }}
                                    </x-nav-link>

                                    <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                                        {{ __('Admin') }}
                                    </x-nav-link>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </nav>
    </div>


    <div class="px-10 mx-auto font-poppins">
        @yield('content')
    </div>




    @yield('script')

</body>