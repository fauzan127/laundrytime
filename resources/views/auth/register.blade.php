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
                    placeholder="812-3456-7890"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="address" :value="__('Alamat')" />
            <textarea id="address" name="address"
                class="block mt-1 w-full border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                rows="3">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        <div class="flex items-center my-5">
            <hr class="flex-grow border-gray-600">
            <span class="px-1 text-gray-500 text-sm font-medium">Atau</span>
            <hr class="flex-grow border-gray-600">
        </div>
        <a href="{{ route('google.login') }}"
            class="bg-gray-400 flex items-center justify-center w-full border border-gray-300 rounded-lg py-2 hover:bg-gray-100 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 mr-2">
                <span class="text-gray-700 font-medium">Login dengan Google</span>
        </a>
        
        <p class="text-center mt-4 text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="hover:underline font-bold hover:text-gray-800">Log In</a>
        </p>
    </form>
</x-guest-layout>
