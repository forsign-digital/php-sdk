<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response for an attachment download.
 */
class AttachmentDownloadResponse extends BaseResponse
{
    /**
     * @var string|null The content type of the attachment
     */
    private ?string $contentType;
    
    /**
     * @var string|null The file name of the attachment
     */
    private ?string $fileName;
    
    /**
     * @var string|null The binary content of the attachment
     */
    private ?string $content;
    
    /**
     * @param array<string, mixed> $data The response data
     * @param string|null $contentType The content type of the attachment
     * @param string|null $fileName The file name of the attachment
     * @param string|null $content The binary content of the attachment
     */
    public function __construct(
        array $data,
        ?string $contentType = null,
        ?string $fileName = null,
        ?string $content = null
    ) {
        parent::__construct($data);
        
        $this->contentType = $contentType;
        $this->fileName = $fileName;
        $this->content = $content;
    }
    
    /**
     * Gets the content type of the attachment.
     *
     * @return string|null The content type of the attachment
     */
    public function getContentType(): ?string
    {
        return $this->contentType ?? $this->data['contentType'] ?? null;
    }
    
    /**
     * Gets the file name of the attachment.
     *
     * @return string|null The file name of the attachment
     */
    public function getFileName(): ?string
    {
        return $this->fileName ?? $this->data['fileName'] ?? null;
    }
    
    /**
     * Gets the binary content of the attachment.
     *
     * @return string|null The binary content of the attachment
     */
    public function getContent(): ?string
    {
        return $this->content ?? $this->data['content'] ?? null;
    }
    
    /**
     * Gets the file extension of the attachment.
     *
     * @return string|null The file extension of the attachment
     */
    public function getFileExtension(): ?string
    {
        $fileName = $this->getFileName();
        
        if ($fileName === null) {
            return null;
        }
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        return $extension !== '' ? $extension : null;
    }
    
    /**
     * Gets the file size in bytes.
     *
     * @return int The file size in bytes
     */
    public function getFileSize(): int
    {
        $content = $this->getContent();
        
        return $content !== null ? strlen($content) : 0;
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
    
    /**
     * Saves the attachment to disk.
     *
     * @param string $path The path where to save the file
     * @return bool Whether the file was saved successfully
     */
    public function saveToFile(string $path): bool
    {
        $content = $this->getContent();
        
        if ($content === null) {
            return false;
        }
        
        return file_put_contents($path, $content) !== false;
    }
    
    /**
     * Gets the attachment content as a Base64-encoded string.
     *
     * @return string|null The attachment content as a Base64-encoded string
     */
    public function getBase64Content(): ?string
    {
        $content = $this->getContent();
        
        return $content !== null ? base64_encode($content) : null;
    }
    
    /**
     * Gets the attachment as a data URI.
     *
     * @return string|null The attachment as a data URI
     */
    public function getDataUri(): ?string
    {
        $contentType = $this->getContentType();
        $base64Content = $this->getBase64Content();
        
        if ($contentType === null || $base64Content === null) {
            return null;
        }
        
        return "data:{$contentType};base64,{$base64Content}";
    }
}
