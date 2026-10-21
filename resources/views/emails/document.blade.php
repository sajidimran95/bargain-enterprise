<x-mail::message>
# {{ $headline }}

{{ $intro }}

@if ($pdf)
A PDF copy is attached to this email.
@endif

Thanks,<br>
{{ \App\Support\CompanyProfile::name() }}
@foreach (\App\Support\CompanyProfile::lines() as $line)
<br>{{ $line }}
@endforeach
</x-mail::message>
