<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Model;

use App\Domains\Hotel\Enum\ReservationStatusEnum;
use App\Domains\User\Model\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $room_id
 * @property int $user_id
 * @property int $status
 * @property int $count_days
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property Carbon $check_in_at
 * @property Carbon $check_out_at
 *
 * @property-read User $user
 * @see Reservation::user()
 *
 * @property-read Room $room
 * @see Reservation::room()
 */
class Reservation extends Model
{
    public const string FIELD_ROOM_ID = 'room_id';
    public const string FIELD_USER_ID = 'user_id';
    public const string FIELD_COUNT_DAYS = 'count_days';
    public const string FIELD_DATE_FROM = 'date_from';
    public const string FIELD_DATE_TO = 'date_to';
    public const string FIELD_CHECK_IN_AT = 'check_in_at';
    public const string FIELD_CHECK_OUT_AT = 'check_out_at';
    public const string FIELD_STATUS = 'status';

    protected $table = 'orders';

    protected $casts = [
        self::FIELD_DATE_FROM => 'datetime',
        self::FIELD_DATE_TO => 'datetime',
        self::FIELD_CHECK_IN_AT => 'datetime',
        self::FIELD_CHECK_OUT_AT => 'datetime',
        self::FIELD_STATUS => ReservationStatusEnum::class,
    ];

    public function isRoomLoaded(): bool
    {
        return $this->relationLoaded('room');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, self::FIELD_ROOM_ID);
    }

    public function isUserLoaded(): bool
    {
        return $this->relationLoaded('user');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::FIELD_USER_ID);
    }
}
