<?php

declare(strict_types=1);

namespace ForSign\Api\SignatureTypes;

use ForSign\Api\Enums\SignatureType;

/**
 * Represents an automatic stamp signature type.
 */
class AutomaticStampSignatureType extends SignatureInformationType
{
    private string $stampId;

    public function __construct(string $stampId)
    {
        parent::__construct(SignatureType::Stamp);
        $this->stampId = $stampId;
    }

    public function getStampId(): string
    {
        return $this->stampId;
    }
}
