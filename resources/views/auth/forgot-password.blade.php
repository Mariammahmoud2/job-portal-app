<x-guest-layout>
    <div class="mb-4 text-sm text-white/50">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white/70" />
            <x-text-input id="email" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center mt-6">
            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

    </form>
</x-guest-layout>