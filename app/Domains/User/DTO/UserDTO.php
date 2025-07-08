<?php

declare(strict_types=1);

namespace App\Domains\User\DTO;

use App\Domains\User\Model\User;

class UserDTO
{
    public function __construct(protected ?int $id = null, protected string $email, protected ?User $authUser = null)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAuthUser(): ?User
    {
        return $this->authUser;
    }
}
