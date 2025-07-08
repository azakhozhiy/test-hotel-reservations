<?php

namespace App\Jobs;

use App\Domains\Hotel\Model\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\RoomReserved as RoomReservedMail;

class RoomReserved implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Reservation $reservation)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->reservation->user->email)->send(new RoomReservedMail($this->reservation));
    }
}
