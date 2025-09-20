<?php

declare(strict_types=1);

namespace ForSign\Api\Enums;

/**
 * Enum for input attachment types.
 */
enum InputAttachmentType: int
{
    case CameraSideBack = 1;
    case CameraSideFront = 2;
    case UploadFile = 4;
    
    /**
     * Converts the enum to a string for display.
     *
     * @return string The input attachment type name
     */
    public function toString(): string
    {
        return match ($this) {
            self::CameraSideBack => 'Camera Side Back',
            self::CameraSideFront => 'Camera Side Front',
            self::UploadFile => 'Upload File',
        };
    }
    
    /**
     * Gets a description of the input attachment type.
     *
     * @return string The input attachment type description
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::CameraSideBack => 'Take a photo of the back side of a document using the camera.',
            self::CameraSideFront => 'Take a photo of the front side of a document using the camera.',
            self::UploadFile => 'Upload a file from the device.',
        };
    }
    
    /**
     * Creates an enum from a string.
     *
     * @param string $value The input attachment type name
     * @return self The enum
     * @throws \InvalidArgumentException If the input attachment type is not supported
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'camerasideback', 'camera side back', 'camera_side_back' => self::CameraSideBack,
            'camerasidefont', 'camera side front', 'camera_side_front' => self::CameraSideFront,
            'uploadfile', 'upload file', 'upload_file' => self::UploadFile,
            default => throw new \InvalidArgumentException("Unsupported input attachment type: {$value}"),
        };
    }
    
    /**
     * Creates an enum from an integer.
     *
     * @param int $value The input attachment type value
     * @return self The enum
     * @throws \InvalidArgumentException If the input attachment type is not supported
     */
    public static function fromInt(int $value): self
    {
        return match ($value) {
            1 => self::CameraSideBack,
            2 => self::CameraSideFront,
            4 => self::UploadFile,
            default => throw new \InvalidArgumentException("Unsupported input attachment type: {$value}"),
        };
    }
}
