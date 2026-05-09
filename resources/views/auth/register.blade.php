<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white/70" />
            <x-text-input id="name" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-white/70" />
            <x-text-input id="email" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white/70" />
            <x-text-input id="password" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white/70" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center mt-6 gap-4">
            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold">
                {{ __('Register') }}
            </button>

            <a class="text-sm text-white/50 hover:text-white/80" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

    </form>
</x-guest-layout>