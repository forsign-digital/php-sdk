<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response for a ZIP file download.
 */
class OperationZipResponse extends BaseResponse
{
    /**
     * Gets the ZIP file name.
     *
     * @return string The ZIP file name
     */
    public function getName(): string
    {
        return (string) ($this->data['name'] ?? '');
    }
    
    /**
     * Gets the ZIP file content as a Base64-encoded string.
     *
     * @return string The ZIP file content as a Base64-encoded string
     */
    public function getBase64File(): string
    {
        return (string) ($this->data['base64File'] ?? '');
    }
    
    /**
     * Gets the ZIP file content as a binary string.
     *
     * @return string The ZIP file content as a binary string
     */
    public function getFileContent(): string
    {
        $base64 = $this->getBase64File();
        
        if (empty($base64)) {
            return '';
        }
        
        return base64_decode($base64);
    }
    
    /**
     * Saves the ZIP file to disk.
     *
     * @param string $path The path where to save the file
     * @return bool Whether the file was saved successfully
     */
    public function saveToFile(string $path): bool
    {
        $content = $this->getFileContent();
        
        if (empty($content)) {
            return false;
        }
        
        return file_put_contents($path, $content) !== false;
    }
    
    /**
     * Gets the file size in bytes.
     *
     * @return int The file size in bytes
     */
    public function getFileSize(): int
    {
        return strlen($this->getFileContent());
    }
    
    /**
     * Gets the file size in a human-readable format.
     *
     * @return string The file size in a human-readable format
     */
    public function getHumanReadableFileSize(): string
    {
        $bytes = $this->getFileSize();
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
