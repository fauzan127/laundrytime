<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <!--No.Hp-->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Nomor Telepon')" />

        <div class="flex items-center border rounded-lg overflow-hidden">
            <!-- Bendera Indonesia -->
            <img src="https://flagcdn.com/w20/id.png" alt="ID" class="w-6 h-4 ml-2">

            <!-- Prefix +62 -->
            <span class="px-2 text-gray-700">+62</span>

            <!-- Input nomor HP -->
            <x-text-input id="phone" 
                class="flex-1 border-0 focus:ring-0" 
                type="tel" 
                name="phone" 
                :value="old('phone')" 
                autocomplete="tel" 
                placeholder="8123456789" />
        </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="address" :value="__('Alamat')" />
            <textarea id="address" name="address"
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900
                       dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                       focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                rows="3">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>
        
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
