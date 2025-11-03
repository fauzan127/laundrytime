@extends('dashboard.layouts.main')

@section('title', 'Profile')

@section('container')
<div class="max-w-5xl mx-auto bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg p-8 mt-6">
    <div class="flex items-center mb-8 border-b border-green-200 pb-4">

        <div class="ml-4">
            <h1 class="text-3xl font-bold text-green-900">Profil Pengguna</h1>
            <p class="text-sm text-green-700">Informasi akun Anda di Laundry Time</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition">
            <label class="block text-sm font-semibold text-gray-600">Nama Lengkap</label>
            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->name }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition">
            <label class="block text-sm font-semibold text-gray-600">Email</label>
            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->email }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition">
            <label class="block text-sm font-semibold text-gray-600">Nomor Telepon</label>
            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->phone ?? 'Belum diisi' }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition">
            <label class="block text-sm font-semibold text-gray-600">Alamat</label>
            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->address ?? 'Belum diisi' }}</p>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('profile.edit') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow transition">
            <span class="material-icons text-sm"></span>
            Edit Profil
        </a>
    </div>
</div>
@endsection
