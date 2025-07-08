<?php

use App\Domains\Hotel\Model\Reservation;
use App\Domains\Hotel\Model\Room;
use App\Domains\User\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(Reservation::FIELD_ROOM_ID);
            $table->unsignedBigInteger(Reservation::FIELD_USER_ID);
            $table->unsignedTinyInteger(Reservation::FIELD_STATUS);
            $table->unsignedInteger(Reservation::FIELD_COUNT_DAYS);
            $table->date(Reservation::FIELD_DATE_FROM);
            $table->date(Reservation::FIELD_DATE_TO);
            $table->timestamp(Reservation::FIELD_CHECK_IN_AT);
            $table->timestamp(Reservation::FIELD_CHECK_OUT_AT);

            $table->timestamps();

            // Foreign Keys
            $room = new Room();
            $table->foreign(Reservation::FIELD_ROOM_ID)
                ->references($room->getKeyName())
                ->on($room->getTable())
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $user = new User();
            $table->foreign(Reservation::FIELD_USER_ID)
                ->references($user->getKeyName())
                ->on($user->getTable())
                ->onDelete('restrict')
                ->onUpdate('restrict');

            // Indexes
            $table->index([
                Reservation::FIELD_ROOM_ID,
                Reservation::FIELD_DATE_FROM,
                Reservation::FIELD_DATE_TO,
                Reservation::FIELD_STATUS,
            ], 'reservations_availability_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
