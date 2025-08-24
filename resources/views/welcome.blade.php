<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ProSeed - Your Project Asset Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-800">
        <div class="bg-black text-white">
            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <a href="#" class="-m-1.5 p-1.5 flex items-center">
                            <img class="h-8 w-auto" src="https://logosandtypes.com/wp-content/uploads/2024/10/burson.svg" alt="ProSeed Logo">
                            <span class="ml-3 text-xl font-bold">ProSeed</span>
                        </a>
                    </div>
                    <div class="lg:flex lg:flex-1 lg:justify-end">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6">Log in <span aria-hidden="true">&rarr;</span></a>
                        @endauth
                    </div>
                </nav>
            </header>

            <div class="relative isolate px-6 pt-14 lg:px-8">
                {{-- Decorative background gradient blobs --}}
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#f4e911] to-[#4f46e5] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>

                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight sm:text-6xl">
                            Manage <span class="font-bold text-[#f4e911]">Project</span> Assets with <span class="font-bold text-[#f4e911]">Speed</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-300">
                            ProSeed, by Burson Indonesia, is an application designed to help you track, manage, and report on all physical assets for various projects, featuring dynamic product attributes and comprehensive transaction histories.
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="{{ route('register') }}" class="rounded-md bg-[#f4e911] px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#f4e911]">Get started</a>
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6">Log in <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Section -->
        <div class="w-full bg-white">
            <video class="w-full h-auto" src="https://cdn.bursonglobal.com/assets/Burson_Motion_v14_Edit3_h264_compressed.mp4" autoplay loop muted playsinline></video>
        </div>


        <!-- Feature Section -->
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Everything you need to manage your inventory</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">From multi-project management to dynamic product attributes, ProSeed provides the flexibility your team needs.</p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4e911]">
                                    <svg class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                </div>
                                Multi-Project Workspace
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Isolate data, users, and settings for each project. Perfect for agencies or teams handling multiple clients.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4e911]">
                                    <svg class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                </div>
                                Dynamic Attributes
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Define unique fields for products in each project, from text and dates to file uploads and dropdowns.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4e911]">
                                    <svg class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-4.994 0v4.992m-12.293-4.993l3.181-3.183a8.25 8.25 0 0111.667 0l3.181 3.183" /></svg>
                                </div>
                                Complete Transaction History
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Track every asset's journey, from creation and loans to returns, complete with condition reports and news links.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4e911]">
                                    <svg class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 12c0 2.132-.873 4.07-2.293 5.464M17.356 2.644A7.5 7.5 0 0121.5 12c0 1.625-.52 3.125-1.396 4.356" /></svg>
                                </div>
                                Export & Reporting
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Easily export your data to Excel. Get detailed reports on product journeys, partner activity, and transaction histories.</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <footer class="bg-white">
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="mt-8 md:order-1 md:mt-0">
                    <p class="text-center text-xs leading-5 text-gray-500">
                        &copy; {{ date('Y') }} ProSeed. Crafted by the Burson Indonesia Data Team.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
