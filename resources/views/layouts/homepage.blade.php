<!DOCTYPE html>
<html>

<head>
    <title>Dashboard TDC</title>
    <!-- <link rel="shortcut icon" type="image/png" href="img/logotdc.png"> -->

    <!-- <link rel="icon" href="{{ asset('img/logotdc.png') }}" type="image/x-icon"> -->
    <link rel="shortcut icon" href="{{ asset('img/logotdc.png') }}" type="image/png">


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!------------------CSS--------------->
    <link rel="stylesheet" href="{{ asset('CSS/style.css') }}" />

    <!------------------BOXICON CSS--------------->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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

    <script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>

    {{-- <style>
        body {
            background-image: url('img/cover.jpg');
            /* Ganti path_to_your_image.jpg dengan path gambar yang benar */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style> --}}

</head>

<body style="background-image: url('/img/cover.jpg'); background-size:cover;">
    <nav class="sidebar close">
        <header>
            <div class="image-text mt-8 ml-3">
                <span class="image ">
                    <img src="{{ asset('img/TC.png') }}"alt="logo">
                </span>

                <div class="text header-text font-semibold font-poppins">
                    <span class=" awal text-blue-600">TDC</span>

                    <span class="akhir">Dashboard.</span>

                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>

        </header>
        <div class="menu-bar mt-8">
            <div class="menu">
                <ul class="menu-link">
                    <li class="nav-link mb-6 @if (Request::is('/')) active @endif"
                        data-tippy-content="Dashboard">
                        <a href="/" class="icon-link">
                            <i class='bx bx-home icon'></i>
                            <span class="text nav-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="nav-link mb-6 @if (request()->routeIs('deployments.*')) active @endif"
                        data-tippy-content="Deployments">
                        <a href='/deployments/calendar' class="icon-link">
                            <i class='bx bx-cloud-upload icon'></i>
                            <span class="text nav-text"> Deployments </span>
                        </a>
                    </li>

                    <li class="nav-link mb-6 @if (request()->routeIs('background-jobs-monitoring.*')) active @endif"
                        data-tippy-content="Background Jobs">
                        <a href="{{ route('background-jobs-monitoring.daily') }}" class="icon-link">
                            <i class='bx bx-desktop icon'></i>
                            <span class="text nav-text"> Background Jobs </span>
                        </a>
                    </li>

                    <li class="nav-link mb-6 @if (request()->routeIs('user-management.*')) active @endif"
                        data-tippy-content="User Management">
                        <a href="{{ route('user-management.request-by-type') }}" class="icon-link">
                            <i class='bx bxs-user-voice icon'></i>
                            <span class="text nav-text"> User Management </span>
                        </a>
                    </li>

                    <li class="nav-link mb-6 @if (request()->routeIs('brisol.*')) active @endif"
                        data-tippy-content="BRISOL">
                        <a href="{{ route('brisol.service-ci') }}" class="icon-link">
                            <i class='bx bx-stats icon'></i>
                            <span class="text nav-text"> BRISOL </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <a href="{{ route('login') }}">
                        <i class='bx bx-user icon'></i>
                        <span class="text nav-text"> ADMIN </span>
                    </a>
                </li>
            </div>
        </div>
        <script src="{{ asset('JS/script.js') }}" type="text/javascript"></script>
    </nav>



    <section class="home">
        <div class="text px-10 mx-auto font-poppins">
            @yield('content')
        </div>
    </section>

    @yield('script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Tippy
            tippy('[data-tippy-content]', {
                placement: 'right', // Letakkan tooltip di sebelah kanan
                arrow: false, // Hilangkan panah
                trigger: 'mouseenter', // Aktifkan tooltip saat mouse masuk
                hideOnClick: true, // Sembunyikan tooltip saat item diklik
                content(reference) {
                    return reference.getAttribute('data-tippy-content');
                },
                onShow(instance) {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar.classList.contains('open')) {
                        instance.disable(); // Matikan tooltip saat sidebar terbuka
                    } else {
                        instance.enable(); // Aktifkan tooltip saat sidebar tertutup
                    }
                }
            });
        });
    </script>


</body>

</html>
