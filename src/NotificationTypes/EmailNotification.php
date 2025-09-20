<?php

declare(strict_types=1);

namespace ForSign\Api\NotificationTypes;

use InvalidArgumentException;

/**
 * Represents an email notification.
 */
class EmailNotification implements NotificationInterface
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be empty.');
        }
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
