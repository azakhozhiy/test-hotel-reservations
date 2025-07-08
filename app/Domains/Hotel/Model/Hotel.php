<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 */
class Hotel extends Model
{
    public const string FIELD_TITLE = 'title';
    public const string FIELD_SLUG = 'slug';

    protected $table = 'hotels';
}
