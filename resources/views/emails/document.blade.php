<x-mail::message>
# {{ $headline }}

{{ $intro }}

@if ($pdf)
A PDF copy is attached to this email.
@endif

Thanks,<br>
{{ config('bargain.company_name', config('app.name')) }}
</x-mail::message>
