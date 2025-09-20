<?php

declare(strict_types=1);

namespace ForSign\Api\Types;

/**
 * Class for attachment file types.
 */
class AttachmentFileType
{
    /**
     * @var string The file extension
     */
    private string $extension;
    
    /**
     * @param string $extension The file extension
     */
    private function __construct(string $extension)
    {
        $this->extension = $extension;
    }
    
    /**
     * Gets the file extension.
     *
     * @return string The file extension
     */
    public function getExtension(): string
    {
        return $this->extension;
    }
    
    /**
     * Gets the MIME type for the file extension.
     *
     * @return string The MIME type
     */
    public function getMimeType(): string
    {
        return match ($this->extension) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'tiff', 'tif' => 'image/tiff',
            default => 'application/octet-stream',
        };
    }
    
    /**
     * Gets a PDF file type.
     *
     * @return self The file type
     */
    public static function PDF(): self
    {
        return new self('pdf');
    }
    
    /**
     * Gets a PNG file type.
     *
     * @return self The file type
     */
    public static function PNG(): self
    {
        return new self('png');
    }
    
    /**
     * Gets a JPG file type.
     *
     * @return self The file type
     */
    public static function JPG(): self
    {
        return new self('jpg');
    }
    
    /**
     * Gets a JPEG file type.
     *
     * @return self The file type
     */
    public static function JPEG(): self
    {
        return new self('jpeg');
    }
    
    /**
     * Gets a TIFF file type.
     *
     * @return self The file type
     */
    public static function TIFF(): self
    {
        return new self('tiff');
    }
    
    /**
     * Gets a TIF file type.
     *
     * @return self The file type
     */
    public static function TIF(): self
    {
        return new self('tif');
    }
    
    /**
     * Creates a file type from a file extension.
     *
     * @param string $extension The file extension
     * @return self The file type
     */
    public static function fromExtension(string $extension): self
    {
        return new self(strtolower($extension));
    }
    
    /**
     * Creates a file type from a MIME type.
     *
     * @param string $mimeType The MIME type
     * @return self The file type
     * @throws \InvalidArgumentException If the MIME type is not supported
     */
    public static function fromMimeType(string $mimeType): self
    {
        return match ($mimeType) {
            'application/pdf' => self::PDF(),
            'image/png' => self::PNG(),
            'image/jpeg' => self::JPEG(),
            'image/tiff' => self::TIFF(),
            default => throw new \InvalidArgumentException("Unsupported MIME type: {$mimeType}"),
        };
    }
    
    /**
     * Creates a file type from a file path.
     *
     * @param string $path The file path
     * @return self The file type
     * @throws \InvalidArgumentException If the file extension is not supported
     */
    public static function fromPath(string $path): self
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if (empty($extension)) {
            throw new \InvalidArgumentException("Could not determine file extension from path: {$path}");
        }
        
        return self::fromExtension($extension);
    }
}
