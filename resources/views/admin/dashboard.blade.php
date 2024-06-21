<x-app-layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800"
            style="position: sticky; top: 0; background-color: white; z-index: 999;">
            <span class="text-blue-800">Selamat Datang,</span>
            <span class="text-yellow-300">{{ Auth::user()->name }} !!</span>
        </h2>
    </x-slot>

    <div class="min-h-screen relative bg-opacity-100"
        style="background-image: url('/img/cover.jpg'); background-size: cover;">
        <div class="py-12 mx-auto max-w-6xl relative flex justify-center items-start" style="height: 100%;">
            <a href="{{ route('user-management.request-by-type') }}" class="box-link">
                <div
                    style="width: 289px; height: 290px; margin-right: 20px; position: relative; background: white; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px; border: 2px rgba(0, 0, 0, 0.30) solid; display: flex; flex-direction: column; justify-content: center; align-items: center; right: 80px;">
                    <div
                        style="text-align: center; color: #152C5B; font-size: 32px; font-family: Poppins; font-weight: 800; text-transform: uppercase; letter-spacing: 0.32px; word-wrap: break-word; margin-bottom: 50px;">
                        User<br />Management
                    </div>
                    <img src="/img/usman.jpg" alt="Your Image" style="width: 141px; height: 113px; margin-left:20px">
                </div>
            </a>

            <a href="{{ route('brisol.service-ci') }}" class="box-link">
                <div
                    style="width: 289px; height: 290px; margin-right: 20px; position: relative; background: white; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px; border: 2px rgba(0, 0, 0, 0.30) solid; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <div
                        style="text-align: center; color: #152C5B; font-size: 32px; font-family: Poppins; font-weight: 800; text-transform: uppercase; letter-spacing: 0.32px; word-wrap: break-word; margin-bottom: 40px;">
                        Bri<br />Solution
                    </div>
                    <img src="/img/Brisol.jpg" alt="Your Image" style="width: 74px; height: 113px; margin-left:0px">
                </div>
            </a>

            <a href="{{ route('deployments.calendar') }}" class="box-link">
                <div
                    style="width: 289px; height: 290px; margin-right: 20px; position: relative; background: white; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px; border: 2px rgba(0, 0, 0, 0.30) solid; display: flex; flex-direction: column; justify-content: center; align-items: center; left:80px;">
                    <div
                        style="text-align: center; color: #152C5B; font-size: 32px; font-family: Poppins; font-weight: 800; text-transform: uppercase; letter-spacing: 0.32px; word-wrap: break-word; margin-bottom: 40px;">
                        Deployments<br /> & CM
                    </div>
                    <img src="/img/deploy.jpg" alt="Your Image"
                        style="width: 115px; height: 107.33px; margin-left:10px">
                </div>
            </a>

        </div>

        <div class="py-5 mx-auto max-w-7xl relative flex justify-center items-start" style="height: 100%;">
            <a href="{{ route('background-jobs-monitoring.daily') }}" class="box-link">
                <div
                    style="width: 289px; height: 290px; margin-right: 20px; position: relative; background: white; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px; border: 2px rgba(0, 0, 0, 0.30) solid; display: flex; flex-direction: column; justify-content: center; align-items: center; right: 30px; bottom:30px">
                    <div
                        style="text-align: center; color: #152C5B; font-size: 32px; font-family: Poppins; font-weight: 800; text-transform: uppercase; letter-spacing: 0.32px; word-wrap: break-word; margin-bottom: 30px;">
                        Background<br />Jobs
                    </div>
                    <img src="/img/jobs.jpg" alt="Your Image" style="width: 110px; height: 115.22px; margin-left:3px">
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}" class="box-link">
                <div
                    style="width: 289px; height: 290px; margin-right: 20px; position: relative; background: white; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px; border: 2px rgba(0, 0, 0, 0.30) solid; display: flex; flex-direction: column; justify-content: center; align-items: center; left: 30px; bottom:30px">
                    <div
                        style="text-align: center; color: #152C5B; font-size: 32px; font-family: Poppins; font-weight: 800; text-transform: uppercase; letter-spacing: 0.32px; word-wrap: break-word; margin-bottom: 40px;">
                        users
                    </div>
                    <img src="/img/users.jpg" alt="Your Image" style="width: 105.8px; height: 115px; margin-left:0px">
                </div>
            </a>

        </div>

    </div>
</x-app-layout>
