<x-mail::message>
# Action Required: Review Onboarded IP Address

A new IP address has been onboarded with the following details:

- **IP Address:** {{ $ipAddress }}
- **Organization:** {{ $organization }}
- **Note/Description:** {{ $note }}
- **Onboarded By:** {{ $requester }}

Please take the appropriate action:

<div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
    <a href="{{ $approveLink }}" style="display: inline-block; padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Approve
    </a>
    <a href="{{ $declineLink }}" style="display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Decline
    </a>
    <a href="{{ $requestInfoFormLink }}" style="display: inline-block; padding: 10px 20px; background-color: yellow; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Request More Information
    </a>
</div>

{{-- <x-mail::button :url="$approveLink">
Approve
</x-mail::button>

<x-mail::button :url="$declineLink">
Decline
</x-mail::button>

<x-mail::button :url="$moreInfoLink">
Request More Information
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
