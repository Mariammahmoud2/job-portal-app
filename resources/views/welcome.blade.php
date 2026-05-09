<x-main-layout title="Job board - Find your dream job">

    <div class="flex flex-col items-center justify-center min-h-screen text-center">

        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <h4 class="text-sm text-white/60 mb-4">Job-board</h4>
            </div>
        </div>

        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <h1 class="text-4xl sm:text-6xl md:text-8xl font-bold mb-6 tracking-tight">
                    <span class="text-white">Find your</span><br />
                    <span class="text-white/60 font-serif">Dream Job</span>
                </h1>
            </div>
        </div>

        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <p class="text-white/60 text-lg mb-6">connect with top employers, and find exciting opportunities</p>
            </div>
        </div>

        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white/10 text-white px-6 py-2 rounded-lg">Create an Account</a>
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-2 rounded-lg">Login</a>
                </div>
            </div>
        </div>

    </div>

</x-main-layout>