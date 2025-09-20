<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

/**
 * DTO for an attachment in an operation member.
 */
class AttachmentOperationMemberDto implements \JsonSerializable
{
    /**
     * @var int The ID of the attachment
     */
    private int $id;
    
    /**
     * @var string The name of the attachment
     */
    private string $name;
    
    /**
     * @var string The description of the attachment
     */
    private string $description;
    
    /**
     * @var bool Whether the attachment is required
     */
    private bool $required;
    
    /**
     * @var array<string> The allowed file types
     */
    private array $fileTypes;
    
    /**
     * @var int The maximum number of files allowed
     */
    private int $filesAllowed;
    
    /**
     * @var array<int> The allowed input attachment types
     */
    private array $inputAttachmentTypes;
    
    /**
     * @param int $id The ID of the attachment
     * @param string $name The name of the attachment
     * @param string $description The description of the attachment
     * @param bool $required Whether the attachment is required
     * @param array<string> $fileTypes The allowed file types
     * @param int $filesAllowed The maximum number of files allowed
     * @param array<int> $inputAttachmentTypes The allowed input attachment types
     */
    public function __construct(
        int $id,
        string $name,
        string $description,
        bool $required,
        array $fileTypes,
        int $filesAllowed,
        array $inputAttachmentTypes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->required = $required;
        $this->fileTypes = $fileTypes;
        $this->filesAllowed = $filesAllowed;
        $this->inputAttachmentTypes = $inputAttachmentTypes;
    }
    
    /**
     * Gets the ID of the attachment.
     *
     * @return int The ID
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Gets the name of the attachment.
     *
     * @return string The name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Gets the description of the attachment.
     *
     * @return string The description
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Gets whether the attachment is required.
     *
     * @return bool Whether the attachment is required
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
    
    /**
     * Gets the allowed file types.
     *
     * @return array<string> The allowed file types
     */
    public function getFileTypes(): array
    {
        return $this->fileTypes;
    }
    
    /**
     * Gets the maximum number of files allowed.
     *
     * @return int The maximum number of files allowed
     */
    public function getFilesAllowed(): int
    {
        return $this->filesAllowed;
    }
    
    /**
     * Gets the allowed input attachment types.
     *
     * @return array<int> The allowed input attachment types
     */
    public function getInputAttachmentTypes(): array
    {
        return $this->inputAttachmentTypes;
    }
    
    /**
     * Converts the DTO to an array for API requests.
     *
     * @return array<string, mixed> The DTO as an array
     */
    public function toArray(): array
    {
        return [
            'Id' => $this->id,
            'Name' => $this->name,
            'Description' => $this->description,
            'Required' => $this->required,
            'FileType' => $this->fileTypes,
            'FilesAllowed' => $this->filesAllowed,
            'InputAttachment' => $this->inputAttachmentTypes,
        ];
    }
    
    /**
     * Serializes the DTO to JSON.
     *
     * @return array<string, mixed> The DTO as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
