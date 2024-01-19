<div class="mt-10 border-t border-gray-100 pt-10">
    <h2 class="text-2xl font-semibold text-gray-900 mb-5">Pendaftaran</h2>
    @auth
    @if($this->isEditor)
            <p class="text-sm text-gray-600">Anda Tidak dapat Mendaftar</p>
    @elseif(!$this->isRegistered)
        <textarea wire:model="message" class="w-full rounded-lg p-4 bg-gray-50 focus:outline-none text-sm text-gray-700 border-gray-200 placeholder:text-gray-400" cols="30" rows="7">
        </textarea>
        <button wire:click="postRegistration()" class="mt-3 inline-flex items-center justify-center h-10 px-4 font-medium tracking-wide text-white transition duration-200 bg-gray-900 rounded-lg hover:bg-gray-800 focus:shadow-outline focus:outline-none">
            Submit Pendaftaran
        </button>
        @else
            <p class="text-sm text-gray-600">Anda sudah terdaftar di kegiatan ini.<a href="{{ route('filament.app.resources.registrations.index') }}"> Detail</a></p>
        @endif
    @else
        <a wire:navigate class="text-yellow-500 underline py-1" href="{{ route('login') }}">Login untuk Mendaftar Acara</a>
    @endauth
</div>

