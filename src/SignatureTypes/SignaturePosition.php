<?php

declare(strict_types=1);

namespace ForSign\Api\SignatureTypes;

use ForSign\Api\Requests\FileInformation;
use InvalidArgumentException;

/**
 * Represents the position of a signature within a document.
 */
class SignaturePosition
{
    private FileInformation $fileInformation;
    private int $page;
    private string $coordinateX;
    private string $coordinateY;
    private bool $printSignature;

    public function __construct(
        FileInformation $fileInformation,
        int $page,
        string $coordinateX,
        string $coordinateY,
        bool $printSignature = true
    ) {
        if ($page <= 0) {
            throw new InvalidArgumentException('Page number must be positive.');
        }

        $this->fileInformation = $fileInformation;
        $this->page = $page;
        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;
        $this->printSignature = $printSignature;
    }

    public function getFileInformation(): FileInformation
    {
        return $this->fileInformation;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getCoordinateX(): string
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): string
    {
        return $this->coordinateY;
    }

    public function shouldPrintSignature(): bool
    {
        return $this->printSignature;
    }
}
