<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response for a member attachment.
 */
class MemberAttachmentResponse extends BaseResponse
{
    /**
     * Gets the attachment ID.
     *
     * @return int The attachment ID
     */
    public function getId(): int
    {
        return (int) ($this->data['id'] ?? 0);
    }
    
    /**
     * Gets the attachment name.
     *
     * @return string The attachment name
     */
    public function getName(): string
    {
        return (string) ($this->data['name'] ?? '');
    }
    
    /**
     * Gets the attachment description.
     *
     * @return string The attachment description
     */
    public function getDescription(): string
    {
        return (string) ($this->data['description'] ?? '');
    }
    
    /**
     * Checks if the attachment is required.
     *
     * @return bool Whether the attachment is required
     */
    public function isRequired(): bool
    {
        return (bool) ($this->data['required'] ?? false);
    }
    
    /**
     * Gets the allowed file types.
     *
     * @return array<string> The allowed file types
     */
    public function getAllowedFileTypes(): array
    {
        return $this->data['fileType'] ?? [];
    }
    
    /**
     * Gets the number of files allowed.
     *
     * @return int The number of files allowed
     */
    public function getFilesAllowed(): int
    {
        return (int) ($this->data['filesAllowed'] ?? 0);
    }
    
    /**
     * Gets the allowed input attachment types.
     *
     * @return array<int> The allowed input attachment types
     */
    public function getAllowedInputAttachmentTypes(): array
    {
        return $this->data['inputAttachment'] ?? [];
    }
    
    /**
     * Checks if the attachment allows camera side back input.
     *
     * @return bool Whether the attachment allows camera side back input
     */
    public function allowsCameraSideBackInput(): bool
    {
        return in_array(1, $this->getAllowedInputAttachmentTypes(), true);
    }
    
    /**
     * Checks if the attachment allows camera side front input.
     *
     * @return bool Whether the attachment allows camera side front input
     */
    public function allowsCameraSideFrontInput(): bool
    {
        return in_array(2, $this->getAllowedInputAttachmentTypes(), true);
    }
    
    /**
     * Checks if the attachment allows file upload input.
     *
     * @return bool Whether the attachment allows file upload input
     */
    public function allowsFileUploadInput(): bool
    {
        return in_array(4, $this->getAllowedInputAttachmentTypes(), true);
    }
    
    /**
     * Gets the uploaded files.
     *
     * @return array<array{id: int, name: string, status: string}> The uploaded files
     */
    public function getUploadedFiles(): array
    {
        return $this->data['files'] ?? [];
    }
    
    /**
     * Checks if the attachment has any uploaded files.
     *
     * @return bool Whether the attachment has any uploaded files
     */
    public function hasUploadedFiles(): bool
    {
        return !empty($this->getUploadedFiles());
    }
    
    /**
     * Gets the status of the attachment.
     *
     * @return string The status of the attachment
     */
    public function getStatus(): string
    {
        return (string) ($this->data['status'] ?? '');
    }
    
    /**
     * Checks if the attachment is approved.
     *
     * @return bool Whether the attachment is approved
     */
    public function isApproved(): bool
    {
        return $this->getStatus() === 'approved';
    }
    
    /**
     * Checks if the attachment is rejected.
     *
     * @return bool Whether the attachment is rejected
     */
    public function isRejected(): bool
    {
        return $this->getStatus() === 'rejected';
    }
    
    /**
     * Checks if the attachment is pending.
     *
     * @return bool Whether the attachment is pending
     */
    public function isPending(): bool
    {
        return $this->getStatus() === 'pending';
    }
    
    /**
     * Gets the rejection reason.
     *
     * @return string|null The rejection reason or null if not rejected
     */
    public function getRejectionReason(): ?string
    {
        return $this->data['rejectionReason'] ?? null;
    }
}
