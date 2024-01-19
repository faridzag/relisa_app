<x-app-layout title="Beranda">
    @section('hero')
    <div class="w-full text-center py-32">
        <h1 class="text-2xl md:text-3xl font-bold text-center lg:text-5xl text-gray-700">
            Selamat datang di <span class="text-blue-500">RELISA</span>
        </h1>
        <p class="text-gray-500 text-lg mt-1">Relawan Lingkungan Sosial atau RELISA adalah platform untuk pemuda-pemuda peduli sesama dengan mengikuti kegiatan/acara relawan</p>
        @auth
        <a class="px-3 py-2 text-lg text-white bg-gray-800 rounded mt-5 inline-block"
            href="http://127.0.0.1:8001/event">Telusuri Acara</a>
        @else
        <a class="px-3 py-2 text-lg text-white bg-gray-800 rounded mt-5 inline-block"
            href="http://127.0.0.1:8001/register">Ayo daftar</a>
        @endauth
        <!--<a href="/send-mail">
            <x-button>Test Email</x-button>
        </a>-->
    </div>
    @endsection

    <div class="mb-10 w-full">
        <hr>

        <h2 class="mt-16 mb-5 text-3xl text-blue-500 font-bold">Terbaru</h2>
        <div class="w-full mb-5">
            <div class="grid w-full grid-cols-3 gap-10">
                @foreach($latestEvents as $event)
                <x-events.event-card :event="$event" class="md:col-span-1 col-span-3" />
                @endforeach
            </div>
        </div>
        <a class="mt-10 block text-center text-lg text-blue-500 font-semibold"
                href="http://127.0.0.1:8001/event">Lihat Selengkapnya</a>
    </div>
</x-app-layout>
