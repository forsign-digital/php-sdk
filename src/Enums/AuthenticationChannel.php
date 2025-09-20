<?php

declare(strict_types=1);

namespace ForSign\Api\Enums;

/**
 * Enum for authentication channels.
 */
enum AuthenticationChannel: int
{
    case Email = 0;
    case SMS = 1;
    case WhatsApp = 2;

    /**
     * Converts the enum to a string for display.
     *
     * @return string The authentication channel name
     */
    public function toString(): string
    {
        return match ($this) {
            self::Email => 'Email',
            self::SMS => 'SMS',
            self::WhatsApp => 'WhatsApp',
        };
    }

    /**
     * Creates an enum from a string.
     *
     * @param string $value The authentication channel name
     * @return self The enum
     * @throws \InvalidArgumentException If the authentication channel is not supported
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'email' => self::Email,
            'sms' => self::SMS,
            'whatsapp' => self::WhatsApp,
            default => throw new \InvalidArgumentException("Unsupported authentication channel: {$value}"),
        };
    }

    /**
     * Creates an enum from an integer.
     *
     * @param int $value The authentication channel value
     * @return self The enum
     * @throws \InvalidArgumentException If the authentication channel is not supported
     */
    public static function fromInt(int $value): self
    {
        return match ($value) {
            0 => self::Email,
            1 => self::SMS,
            2 => self::WhatsApp,
            default => throw new \InvalidArgumentException("Unsupported authentication channel: {$value}"),
        };
    }
}