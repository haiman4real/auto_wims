<x-mail::message>

# Appointment Confirmation

Dear {{ $appointmentData['fullname'] }},

Your appointment has been successfully scheduled with the following details:

@component('mail::table')
| Field             | Details                        |
| ----------------- | ------------------------------ |
| **Vehicle Make**   | {{ $appointmentData['veh_make'] }}         |
| **Vehicle Model**  | {{ $appointmentData['veh_model'] }}        |
| **Vehicle Year**   | {{ $appointmentData['veh_year'] }}         |
| **Plate Number**   | {{ $appointmentData['plate_num'] }}        |
| **Appointment Date** | {{ \Carbon\Carbon::parse($appointmentData['appointment_date'])->toFormattedDateString() }} |
| **Appointment Time** | {{ $appointmentData['appointment_time'] }} |
@endcomponent

Thank you for booking with us!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
