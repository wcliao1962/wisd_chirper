<x-mail::message>
# New Chirp Created by {{$chirp->user->name}}

    {{$chirp->message}}

<x-mail::button :url="$url">
Go to chirp
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
