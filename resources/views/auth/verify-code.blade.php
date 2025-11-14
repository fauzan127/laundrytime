<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Email') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Kami telah mengirim kode verifikasi ke email Anda. Masukkan kode tersebut di bawah ini untuk menyelesaikan verifikasi.') }}
                    </div>

                    @if (session('status') == 'verification-code-sent')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('Kode verifikasi baru telah dikirim ke email Anda.') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.code.verify') }}">
                        @csrf

                        <!-- Verification Code -->
                        <div>
                            <x-input-label for="code" :value="__('Kode Verifikasi')" />
                            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus maxlength="6" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Verifikasi') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('verification.code.resend') }}" class="mt-4">
                        @csrf

                        <div class="items-center justify-center">
                            <x-primary-button type="submit" class="bg-gray-500 hover:bg-gray-600">
                                {{ __('Kirim Ulang Kode') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
