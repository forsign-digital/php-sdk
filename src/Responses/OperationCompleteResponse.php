<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response for a completed operation.
 */
class OperationCompleteResponse extends BaseResponse
{
    /**
     * Gets the success status.
     *
     * @return bool Whether the operation was completed successfully
     */
    public function isSuccess(): bool
    {
        return (bool) ($this->data['success'] ?? false);
    }
    
    /**
     * Gets the status code.
     *
     * @return int The status code
     */
    public function getStatusCode(): int
    {
        return (int) ($this->data['statusCode'] ?? 0);
    }
    
    /**
     * Gets the message.
     *
     * @return string The message
     */
    public function getMessage(): string
    {
        return (string) ($this->data['message'] ?? '');
    }
    
    /**
     * Gets the data.
     *
     * @return array<string, mixed> The data
     */
    public function getData(): array
    {
        return $this->data['data'] ?? [];
    }
    
    /**
     * Gets the operation ID.
     *
     * @return int The operation ID
     */
    public function getOperationId(): int
    {
        return (int) ($this->getData()['id'] ?? 0);
    }
    
    /**
     * Gets the operation name.
     *
     * @return string The operation name
     */
    public function getOperationName(): string
    {
        return (string) ($this->getData()['name'] ?? '');
    }
    
    /**
     * Gets the operation status.
     *
     * @return string The operation status
     */
    public function getOperationStatus(): string
    {
        return (string) ($this->getData()['status'] ?? '');
    }
    
    /**
     * Gets the completion date.
     *
     * @return \DateTime|null The completion date or null if not available
     */
    public function getCompletionDate(): ?\DateTime
    {
        $date = $this->getData()['completionDate'] ?? null;
        
        if ($date === null) {
            return null;
        }
        
        try {
            return new \DateTime($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}
