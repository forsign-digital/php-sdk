<?php

declare(strict_types=1);

namespace ForSign\Api\AuthenticationTypes;

use InvalidArgumentException;

/**
 * Represents double authentication by SMS.
 */
class SmsDoubleAuthentication implements DoubleAuthenticationInterface
{
    private string $phone;

    public function __construct(string $phone)
    {
        if (empty($phone)) {
            throw new InvalidArgumentException('Phone number cannot be null or empty.');
        }
        $this->phone = $phone;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}