<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Repository;

use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function query(): Builder
    {
        return User::query();
    }

    public function findByEmail(string $email): User
    {
        return $this->query()
            ->where('email', $email)
            ->firstOrFail();
    }

    public function checkExists(string $field, string $value): bool
    {
        return $this->query()
            ->where($field, $value)
            ->exists();
    }
}
