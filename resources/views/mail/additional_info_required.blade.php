<x-mail::message>
# Additional Information Required

Hello,

Additional information has been requested for the onboarded IP address **{{ $allowedIp->ip_address }}**.

## Details Requested:
{{ $detailsRequested }}

Please provide the requested information at your earliest convenience using this link below:
<x-mail::button :url="$link">
    Provide Information
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
