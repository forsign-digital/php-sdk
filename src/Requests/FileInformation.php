<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

/**
 * DTO for holding information about an uploaded file.
 */
class FileInformation
{
    private string $fileId;
    private string $fileName;

    public function __construct(string $fileId, string $fileName)
    {
        $this->fileId = $fileId;
        $this->fileName = $fileName;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
