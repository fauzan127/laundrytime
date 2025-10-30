@extends('dashboard.layouts.main')

@section('title', 'Profile')

@section('container')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Profile</h1>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <p class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Edit Profile
        </a>
    </div>
</div>
@endsection
