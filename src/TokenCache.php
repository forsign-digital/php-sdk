<?php

declare(strict_types=1);

namespace ForSign\Api;

/**
 * Cache for storing file information.
 */
class TokenCache
{
    private array $fileInformation = [];

    /**
     * Stores file information for later use.
     *
     * @param string $fileId The ID of the file
     * @param string $fileName The name of the file
     * @return void
     */
    public function setFileInformation(string $fileId, string $fileName): void
    {
        $this->fileInformation[$fileId] = [
            'fileId' => $fileId,
            'fileName' => $fileName,
        ];
    }

    /**
     * Gets stored file information by ID.
     *
     * @param string $fileId The ID of the file
     * @return array|null The file information or null if not found
     */
    public function getFileInformation(string $fileId): ?array
    {
        return $this->fileInformation[$fileId] ?? null;
    }

    /**
     * Gets all stored file information.
     *
     * @return array All file information
     */
    public function getAllFileInformation(): array
    {
        return $this->fileInformation;
    }

    /**
     * Clears all stored file information.
     *
     * @return void
     */
    public function clearFileInformation(): void
    {
        $this->fileInformation = [];
    }

    /**
     * Clears all stored data.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->clearFileInformation();
    }
}