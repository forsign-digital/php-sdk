<?php

declare(strict_types=1);

namespace ForSign\Api\SignatureTypes;

use ForSign\Api\Enums\SignatureType;

/**
 * Abstract base class for signature type information.
 */
abstract class SignatureInformationType
{
    protected bool $printSignature;
    protected SignatureType $signatureType;

    public function __construct(SignatureType $signatureType, bool $printSignature = true)
    {
        $this->signatureType = $signatureType;
        $this->printSignature = $printSignature;
    }

    public function shouldPrintSignature(): bool
    {
        return $this->printSignature;
    }

    public function getSignatureType(): SignatureType
    {
        return $this->signatureType;
    }
}
