<?php

declare(strict_types=1);

namespace ForSign\Api\AuthenticationTypes;

use InvalidArgumentException;

/**
 * Represents double authentication by email.
 */
class EmailDoubleAuthentication implements DoubleAuthenticationInterface
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be null or empty.');
        }
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
