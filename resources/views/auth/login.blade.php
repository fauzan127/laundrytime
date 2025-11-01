<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex justify-start mt-1">
                <input id="remember_me" type="checkbox" class="rounded bg-white border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-1 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="underline items-end justify-end text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            
        </div>
        
        <div class="flex items-end justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        <div class="flex items-center my-5">
            <hr class="flex-grow border-gray-600">
            <span class="px-1 text-gray-500 text-sm font-medium">Atau</span>
            <hr class="flex-grow border-gray-600">
        </div>
        <a href="{{ route('google.login') }}"
            class="bg-gray-300 flex items-center justify-center w-full border border-gray-300 rounded-lg py-2 hover:bg-gray-100 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 mr-2">
                <span class="text-gray-700 font-medium">Login dengan Google</span>
        </a>

        <p class="text-center mt-4 text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="hover:underline font-bold hover:text-gray-800">Sign Up</a>
        </p>
    </form>
</x-guest-layout>
