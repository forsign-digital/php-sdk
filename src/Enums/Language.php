<?php

declare(strict_types=1);

namespace ForSign\Api\Enums;

/**
 * Enum for supported languages.
 */
enum Language: string
{
    case Portuguese = 'pt-br';
    case English = 'en-us';
    case Spanish = 'es-es';
    
    /**
     * Converts the enum to a string for API requests.
     *
     * @return string The language code
     */
    public function toApiString(): string
    {
        return $this->value;
    }
    
    /**
     * Creates an enum from a string.
     *
     * @param string $value The language code
     * @return self The enum
     * @throws \InvalidArgumentException If the language code is not supported
     */
    public static function fromString(string $value): self
    {
        return match ($value) {
            'pt-br' => self::Portuguese,
            'en-us' => self::English,
            'es-es' => self::Spanish,
            default => throw new \InvalidArgumentException("Unsupported language: {$value}"),
        };
    }
}
