<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        /* Menambahkan styling untuk teks TDC Dashboard */
        .tdc-dashboard {
            font-size: 3rem; /* ukuran font */
            font-weight: bold; /* ketebalan font */
            font-family: 'Poppins', sans-serif; /* font family */
            margin-top: 0px; /* jarak dari atas */
            margin-bottom: 20px; /* jarak dari bawah */
            text-align: center; /* posisi teks di tengah */
        }

        .authentication-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
            /* Sesuaikan dengan kebutuhan */
        }
    </style>
</head>

<body>
    <div class="absolute mt-10 mb-10 left-0">
        <img src="img/logo.png">
    </div>

    <x-guest-layout>

        <x-authentication-card>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                <!-- Menambahkan div untuk teks TDC Dashboard -->
                <div class="tdc-dashboard">
                    <a href="" class="text-3xl font-semibold font-poppins">
                        <span class="text-blue-600">TDC</span>Dashboard.
                    </a>
                </div>

                @csrf

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button class="ml-4">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>

        </x-authentication-card>
    </x-guest-layout>
</body>

</html>
