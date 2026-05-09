<x-guest-layout>
    <div class="mb-4 text-sm text-white/50">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-white/70" />
            <x-text-input id="password" class="block mt-1 w-full bg-white/5 border-0 text-white rounded-lg"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center mt-6">
            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold">
                {{ __('Confirm') }}
            </button>
        </div>

    </form>
</x-guest-layout>