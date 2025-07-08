<?php

use App\Domains\Hotel\Model\Hotel;
use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', static function (Blueprint $table) {
            $table->id();
            $table->string(Room::FIELD_TITLE);
            $table->string(Room::FIELD_SLUG);
            $table->unsignedBigInteger(Room::FIELD_HOTEL_ID);
            $table->string(Room::FIELD_CHECK_IN_TIME);
            $table->string(Room::FIELD_CHECK_OUT_TIME);
            $table->timestamps();

            // Foreign Keys
            $hotel = new Hotel();
            $table->foreign(Room::FIELD_HOTEL_ID)
                ->references($hotel->getKeyName())
                ->on($hotel->getTable())
                ->onDelete('restrict')
                ->onUpdate('restrict');

            // Indexes
            $table->index(Room::FIELD_HOTEL_ID);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
