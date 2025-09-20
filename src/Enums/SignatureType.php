<?php

declare(strict_types=1);

namespace ForSign\Api\Enums;

/**
 * Enum for signature types.
 */
enum SignatureType: int
{
    case Click = 0;
    case Draw = 1;
    case Text = 2;
    case Stamp = 3;
    case UserChoice = 4;
    case AutomaticStamp = 5;
    case Rubric = 6;
    case Certificate = 7;

    /**
     * Converts the enum to a string for display.
     *
     * @return string The signature type name
     */
    public function toString(): string
    {
        return match ($this) {
            self::Click => 'Click',
            self::Draw => 'Draw',
            self::Text => 'Text',
            self::Stamp => 'Stamp',
            self::UserChoice => 'User Choice',
            self::AutomaticStamp => 'Automatic Stamp',
            self::Rubric => 'Rubric',
            self::Certificate => 'Certificate',
        };
    }

    /**
     * Gets a description of the signature type.
     *
     * @return string The signature type description
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::Click => 'Signature by clicking, typically representing a simple consent.',
            self::Draw => 'Signature by drawing, often used for a handwritten signature.',
            self::Text => 'Signature in text form.',
            self::Stamp => 'Signature using a predefined stamp or seal.',
            self::UserChoice => 'Allows the user to choose the type of signature.',
            self::AutomaticStamp => 'Automatic stamp signature, where the stamp is applied automatically.',
            self::Rubric => 'Rubric signature, typically a small and stylized handwritten signature or text.',
            self::Certificate => 'Certificate-based signature, using a local digital certificate for enhanced security.',
        };
    }

    /**
     * Creates an enum from a string.
     *
     * @param string $value The signature type name
     * @return self The enum
     * @throws \InvalidArgumentException If the signature type is not supported
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'click' => self::Click,
            'draw' => self::Draw,
            'text' => self::Text,
            'stamp' => self::Stamp,
            'userchoice', 'user choice', 'user_choice' => self::UserChoice,
            'automaticstamp', 'automatic stamp', 'automatic_stamp' => self::AutomaticStamp,
            'rubric' => self::Rubric,
            'certificate' => self::Certificate,
            default => throw new \InvalidArgumentException("Unsupported signature type: {$value}"),
        };
    }

    /**
     * Creates an enum from an integer.
     *
     * @param int $value The signature type value
     * @return self The enum
     * @throws \InvalidArgumentException If the signature type is not supported
     */
    public static function fromInt(int $value): self
    {
        return match ($value) {
            0 => self::Click,
            1 => self::Draw,
            2 => self::Text,
            3 => self::Stamp,
            4 => self::UserChoice,
            5 => self::AutomaticStamp,
            6 => self::Rubric,
            7 => self::Certificate,
            default => throw new \InvalidArgumentException("Unsupported signature type: {$value}"),
        };
    }
}