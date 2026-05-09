<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white/70" />
            <x-text-input id="email" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white/70" />
            <x-text-input id="password" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-0 text-purple-500" name="remember">
                <span class="ms-2 text-sm text-white/50">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center mt-6 gap-4">
            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold">
                {{ __('Log in') }}
            </button>

            <a class="text-sm text-white/50 hover:text-white/80" href="{{ route('register') }}">
                {{ __("Don't have an account?") }}
            </a>
        </div>

    </form>
</x-guest-layout>