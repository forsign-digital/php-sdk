<?php

declare(strict_types=1);

namespace ForSign\Api\Forms;

use ForSign\Api\Requests\FileInformation;
use InvalidArgumentException;

/**
 * Represents the position of a form field in a document.
 */
class FormFieldPosition
{
    private FileInformation $fileInfo;
    private int $page;
    private string $coordinateX;
    private string $coordinateY;

    public function __construct(FileInformation $fileInformation, int $page, string $coordinateX, string $coordinateY)
    {
        if ($page <= 0) {
            throw new InvalidArgumentException('Page number must be positive.');
        }

        $this->fileInfo = $fileInformation;
        $this->page = $page;
        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;

    }

    public function getFileInfo(): FileInformation
    {
        return $this->fileInfo;
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
}
