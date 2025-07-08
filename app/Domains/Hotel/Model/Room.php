<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $hotel_id
 * @property string $check_in_time
 * @property string $check_out_time
 *
 * @property-read Hotel $hotel
 * @see Room::hotel()
 */
class Room extends Model
{
    public const string FIELD_TITLE = 'title';
    public const string FIELD_SLUG = 'slug';
    public const string FIELD_HOTEL_ID = 'hotel_id';
    public const string FIELD_CHECK_IN_TIME = 'check_in_time';
    public const string FIELD_CHECK_OUT_TIME = 'check_out_time';

    protected $table = 'rooms';

    public function isHotelLoaded(): bool
    {
        return $this->relationLoaded('hotel');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, self::FIELD_HOTEL_ID);
    }

    public function setHotelRelation(Hotel $hotel): static
    {
        $this->setRelation('hotel', $hotel);

        return $this;
    }

    public function getCheckInHours(): int
    {
        return (int)explode(':', $this->check_in_time)[0];
    }

    public function getCheckInMinutes(): int
    {
        return (int)explode(':', $this->check_in_time)[1];
    }

    public function getCheckOutHours(): int
    {
        return (int)explode(':', $this->check_out_time)[0];
    }

    public function getCheckOutMinutes(): int
    {
        return (int)explode(':', $this->check_out_time)[1];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, Reservation::FIELD_ROOM_ID);
    }
}
