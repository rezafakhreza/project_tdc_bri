<!DOCTYPE html>
<html>

<head>
    @yield('title')

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        #navbar {
            position: absolute;
            /* Keeps the navbar at the top */
            top: 0;
            width: 100%;
            z-index: 1000;
            /* border-bottom: 3px solid #DDE5E9; */
            background-color: white;
            /* Optional: to ensure navbar background is not transparent */
        }

        .navbar-link {
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar-link:hover {
            color: #2B4CDE;
        }
    </style>

</head>

<body>
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

                                    <x-nav-link href="{{ route('background-jobs-monitoring.daily') }}" :active="request()->routeIs('background-jobs-monitoring.daily')">
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

    <div class="px-10 mx-auto font-poppins" style="margin-top: 130px">
        @yield('content')
    </div>

    @yield('script')

</body>

</html>