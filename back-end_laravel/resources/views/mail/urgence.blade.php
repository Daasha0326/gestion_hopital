<x-mail::message>
{{ $username }}
{{ $lieu }} 
{{ $heure }}
{{ $description }}
{{ $photo }}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
