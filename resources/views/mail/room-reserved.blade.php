@php
    /** @var \App\Domains\Hotel\Model\Reservation $reservation */
@endphp

<div>
    Hotel: {{ $reservation->room->hotel->title }}
</div>
<div>
    Room: {{$reservation->room->title}}
</div>
<div>
    Check-In: {{$reservation->check_in_at}}
</div>
<div>
    Check-Out: {{$reservation->check_out_at}}
</div>
