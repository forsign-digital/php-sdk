<?php

declare(strict_types=1);

namespace ForSign\Api\SignatureTypes;

use ForSign\Api\Enums\SignatureType;

/**
 * Represents a default signature type configuration.
 */
class DefaultSignatureType extends SignatureInformationType
{
    public function __construct(SignatureType $signatureType, bool $printSignature = true)
    {
        parent::__construct($signatureType, $printSignature);
    }
}
