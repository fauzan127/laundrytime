@extends('dashboard.layouts.main')
@section('container')
<div class="py-2 px-4">
@if(session('success'))
<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif
<h1 id="greeting" class="text-2xl font-bold"></h1>
<p class="mt-2 text-gray-600">This is your dashboard where you can manage your content.</p>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let greetingText = "";
        let emoji = "";
        const hour = new Date().getHours();

        if (hour >= 5 && hour < 12) {
            greetingText = "Good Morning";
            emoji = "🌅";
        } else if (hour >= 12 && hour < 15) {
            greetingText = "Good Afternoon";
            emoji = "🌞";
        } else if (hour >= 15 && hour < 18) {
            greetingText = "Good Evening";
            emoji = "🌇";
        } else {
            greetingText = "Good Night";
            emoji = "🌙";
        }
        // Menyisipkan nama pengguna dari Laravel
        const userName = @json(Auth::user()->name);
        document.getElementById("greeting").innerHTML = `${greetingText}, ${userName}! ${emoji} `;

        // Animasi Fade-in dan Slide-up
        setTimeout(() => {
            greetingElement.classList.remove('opacity-0', 'translate-y-5');
        }, 300);
    });
</script>
@endsection
