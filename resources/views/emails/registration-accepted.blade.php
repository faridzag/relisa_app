<x-mail::message>
# Selamat

Pendaftaran Anda Diterima. Ikuti link berikut untuk masuk ke grup ...

<x-mail::button :url="$url" color="success">
Link
</x-mail::button>

Terimakasih,<br>
{{ config('app.name') }}
</x-mail::message>
