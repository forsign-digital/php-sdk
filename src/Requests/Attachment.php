<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

use ForSign\Api\Enums\InputAttachmentType;
use ForSign\Api\Types\AttachmentFileType;

/**
 * Request for an attachment.
 */
class Attachment implements \JsonSerializable
{
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
     * @var array<AttachmentFileType> The allowed file types
     */
    private array $fileTypesAllowed = [];
    
    /**
     * @var int The maximum number of files allowed
     */
    private int $maxFilesAllowed = 1;
    
    /**
     * @var array<InputAttachmentType> The allowed input attachment types
     */
    private array $inputAttachmentTypesAllowed = [];
    
    /**
     * @param string $name The name of the attachment
     * @param string $description The description of the attachment
     * @param bool $required Whether the attachment is required
     */
    public function __construct(string $name, string $description, bool $required)
    {
        $this->name = $name;
        $this->description = $description;
        $this->required = $required;
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
     * Sets the name of the attachment.
     *
     * @param string $name The name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
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
     * Sets the description of the attachment.
     *
     * @param string $description The description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
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
     * Sets whether the attachment is required.
     *
     * @param bool $required Whether the attachment is required
     * @return self
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }
    
    /**
     * Gets the allowed file types.
     *
     * @return array<AttachmentFileType> The allowed file types
     */
    public function getFileTypesAllowed(): array
    {
        return $this->fileTypesAllowed;
    }
    
    /**
     * Sets the allowed file types.
     *
     * @param array<AttachmentFileType> $fileTypesAllowed The allowed file types
     * @return self
     */
    public function setFileTypesAllowed(array $fileTypesAllowed): self
    {
        $this->fileTypesAllowed = $fileTypesAllowed;
        return $this;
    }
    
    /**
     * Adds an allowed file type.
     *
     * @param AttachmentFileType $fileType The file type
     * @return self
     */
    public function permitFileType(AttachmentFileType $fileType): self
    {
        $this->fileTypesAllowed[] = $fileType;
        return $this;
    }
    
    /**
     * Gets the maximum number of files allowed.
     *
     * @return int The maximum number of files allowed
     */
    public function getMaxFilesAllowed(): int
    {
        return $this->maxFilesAllowed;
    }
    
    /**
     * Sets the maximum number of files allowed.
     *
     * @param int $maxFilesAllowed The maximum number of files allowed
     * @return self
     */
    public function setMaxFilesAllowed(int $maxFilesAllowed): self
    {
        $this->maxFilesAllowed = $maxFilesAllowed;
        return $this;
    }
    
    /**
     * Gets the allowed input attachment types.
     *
     * @return array<InputAttachmentType> The allowed input attachment types
     */
    public function getInputAttachmentTypesAllowed(): array
    {
        return $this->inputAttachmentTypesAllowed;
    }
    
    /**
     * Sets the allowed input attachment types.
     *
     * @param array<InputAttachmentType> $inputAttachmentTypesAllowed The allowed input attachment types
     * @return self
     */
    public function setInputAttachmentTypesAllowed(array $inputAttachmentTypesAllowed): self
    {
        $this->inputAttachmentTypesAllowed = $inputAttachmentTypesAllowed;
        return $this;
    }
    
    /**
     * Adds an allowed input attachment type.
     *
     * @param InputAttachmentType $inputAttachmentType The input attachment type
     * @return self
     */
    public function permitAttachmentByInput(InputAttachmentType $inputAttachmentType): self
    {
        $this->inputAttachmentTypesAllowed[] = $inputAttachmentType;
        return $this;
    }
    
    /**
     * Converts the attachment to an array for API requests.
     *
     * @return array<string, mixed> The attachment as an array
     */
    public function toArray(): array
    {
        return [
            'Name' => $this->name,
            'Description' => $this->description,
            'Required' => $this->required,
            'FileType' => array_map(fn($fileType) => $fileType->getExtension(), $this->fileTypesAllowed),
            'FilesAllowed' => $this->maxFilesAllowed,
            'InputAttachment' => array_map(fn($inputAttachmentType) => $inputAttachmentType->value, $this->inputAttachmentTypesAllowed),
        ];
    }
    
    /**
     * Serializes the attachment to JSON.
     *
     * @return array<string, mixed> The attachment as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
    
    /**
     * Converts the attachment to an AttachmentOperationMemberDto.
     *
     * @return AttachmentOperationMemberDto The attachment as an AttachmentOperationMemberDto
     */
    public function convert(): AttachmentOperationMemberDto
    {
        return new AttachmentOperationMemberDto(
            0, // ID will be assigned by the API
            $this->name,
            $this->description,
            $this->required,
            array_map(fn($fileType) => $fileType->getExtension(), $this->fileTypesAllowed),
            $this->maxFilesAllowed,
            array_map(fn($inputAttachmentType) => $inputAttachmentType->value, $this->inputAttachmentTypesAllowed)
        );
    }
}
