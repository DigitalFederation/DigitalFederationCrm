@component('mail::message')
    # New Diving Log Validation Request

    {{ $validatorName }},

    You have received a new request to validate a diving log. Your expertise is valuable in ensuring the accuracy and safety of our diving records.

    ## Diving Log Details:

    @component('mail::panel')
        - **Diver**: {{ $divingLog->individual->full_name }}
        - **Date**: {{ date('d/m/Y h:i', strtotime($divingLog->date_and_time)) }}
        - **Dive Type**: {{ $divingLog->dive_type->name }}
    @endcomponent

    ## Action Required:

    Please review the diving log and validate it if the information is correct. Your prompt attention to this matter is appreciated.

    @component('mail::button', ['url' => $url])
        Review Diving Log
    @endcomponent

    If you have any questions or concerns, please don't hesitate to contact us.

    Best regards,<br>
    {{ config('app.name') }} Team
@endcomponent
