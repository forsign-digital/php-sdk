<?php

declare(strict_types=1);

namespace ForSign\Api\Repositories;

use ForSign\Api\Client;
use ForSign\Api\Exceptions\ApiException;
use ForSign\Api\Exceptions\ValidationException;
use ForSign\Api\Requests\OperationRequest;
use ForSign\Api\Responses\OperationCompleteResponse;
use ForSign\Api\Responses\OperationCreatedResponse;
use ForSign\Api\Responses\OperationCancelResponse;
use ForSign\Api\Responses\OperationZipResponse;
use ForSign\Api\Responses\OperationSetAutomaticCompletionResponse;
use ForSign\Api\Responses\OperationSetManualCompletionResponse;
use ForSign\Api\Responses\MemberAttachmentResponse;
use ForSign\Api\Responses\AttachmentDownloadResponse;

/**
 * Repository for operation-related API calls.
 */
class OperationRepository
{
    private Client $client;
    
    /**
     * @param Client $client The API client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * Creates a new operation.
     *
     * @param OperationRequest $request The operation request
     * @return OperationCreatedResponse The created operation response
     * @throws ApiException If the API request fails
     * @throws ValidationException If the request contains invalid data
     */
    public function create(OperationRequest $request): OperationCreatedResponse
    {
        $response = $this->client->request('POST', '/api/v1/operation', [
            'json' => $request->toArray(),
            'headers' => [
                'Content-Language' => 'pt-BR',
            ],
        ]);
        
        if (!isset($response['data']['data'])) {
            throw new ApiException('Invalid response format from server');
        }
        
        return new OperationCreatedResponse($response['data']['data']);
    }
    
    /**
     * Completes an operation.
     *
     * @param int $operationId The ID of the operation to complete
     * @return OperationCompleteResponse The completion response
     * @throws ApiException If the API request fails
     */
    public function complete(int $operationId): OperationCompleteResponse
    {
        if ($operationId <= 0) {
            throw new \InvalidArgumentException('Operation ID must be greater than zero.');
        }
        
        $response = $this->client->request('POST', "/api/v2/operation/{$operationId}/complete");
        
        return new OperationCompleteResponse($response);
    }
    
    /**
     * Cancels an operation.
     *
     * @param int $operationId The ID of the operation to cancel
     * @param string $message The reason for cancellation
     * @return OperationCancelResponse The cancellation response
     * @throws ApiException If the API request fails
     */
    public function cancel(int $operationId, string $message): OperationCancelResponse
    {
        if ($operationId <= 0) {
            throw new \InvalidArgumentException('Operation ID must be greater than zero.');
        }
        
        if (empty($message)) {
            throw new \InvalidArgumentException('Cancellation message cannot be empty.');
        }
        
        $response = $this->client->request('POST', "/api/v2/operation/{$operationId}/cancel", [
            'json' => [
                'message' => $message,
            ],
        ]);
        
        return new OperationCancelResponse($response);
    }
    
    /**
     * Sets an operation to automatic completion mode.
     *
     * @param int $operationId The ID of the operation
     * @param \DateTime $endDate The date when the operation should be automatically completed
     * @return OperationSetAutomaticCompletionResponse The response
     * @throws ApiException If the API request fails
     */
    public function setAutomaticCompletion(int $operationId, \DateTime $endDate): OperationSetAutomaticCompletionResponse
    {
        if ($operationId <= 0) {
            throw new \InvalidArgumentException('Operation ID must be greater than zero.');
        }
        
        $now = new \DateTime();
        $now->add(new \DateInterval('PT1H'));
        
        if ($endDate <= $now) {
            throw new \InvalidArgumentException('End date must be at least one hour in the future.');
        }
        
        $response = $this->client->request('PATCH', "/api/v2/operation/{$operationId}/set-automatic-completion", [
            'json' => [
                'endDate' => $endDate->format('c'),
            ],
        ]);
        
        return new OperationSetAutomaticCompletionResponse($response);
    }
    
    /**
     * Sets an operation to manual completion mode.
     *
     * @param int $operationId The ID of the operation
     * @return OperationSetManualCompletionResponse The response
     * @throws ApiException If the API request fails
     */
    public function setManualCompletion(int $operationId): OperationSetManualCompletionResponse
    {
        if ($operationId <= 0) {
            throw new \InvalidArgumentException('Operation ID must be greater than zero.');
        }
        
        $response = $this->client->request('PATCH', "/api/v2/operation/{$operationId}/set-manual-completion");
        
        return new OperationSetManualCompletionResponse($response);
    }
    
    /**
     * Downloads all files of an operation as a ZIP archive.
     *
     * @param int $operationId The ID of the operation
     * @return OperationZipResponse The ZIP file information
     * @throws ApiException If the API request fails
     */
    public function downloadZip(int $operationId): OperationZipResponse
    {
        if ($operationId <= 0) {
            throw new \InvalidArgumentException('Operation ID must be greater than zero.');
        }
        
        $response = $this->client->request('GET', "/api/v1/operation/{$operationId}/zip");
        
        if (!isset($response['data'])) {
            throw new ApiException('Invalid response format from server');
        }
        
        return new OperationZipResponse($response['data']);
    }
    
    /**
     * Gets the list of attachments associated with a specific member.
     *
     * @param int $memberId The ID of the member
     * @return array<MemberAttachmentResponse> The list of attachments
     * @throws ApiException If the API request fails
     */
    public function getMemberAttachments(int $memberId): array
    {
        if ($memberId <= 0) {
            throw new \InvalidArgumentException('Member ID must be greater than zero.');
        }
        
        $response = $this->client->request('GET', "/api/v2/attachment/member/{$memberId}");
        
        $attachments = [];
        foreach ($response as $attachment) {
            $attachments[] = new MemberAttachmentResponse($attachment);
        }
        
        return $attachments;
    }
    
    /**
     * Approves attachments for a specific operation member.
     *
     * @param int $operationMemberId The ID of the operation member
     * @param array<int> $attachmentIds The list of attachment IDs to approve
     * @return void
     * @throws ApiException If the API request fails
     */
    public function approveAttachments(int $operationMemberId, array $attachmentIds): void
    {
        if ($operationMemberId <= 0) {
            throw new \InvalidArgumentException('Operation member ID must be greater than zero.');
        }
        
        if (empty($attachmentIds)) {
            throw new \InvalidArgumentException('Attachment IDs cannot be empty.');
        }
        
        $this->client->request('POST', '/api/v2/attachment/approve', [
            'json' => [
                'operationMemberId' => $operationMemberId,
                'attachmentIds' => $attachmentIds,
            ],
        ]);
    }
    
    /**
     * Rejects attachments for a specific operation member with reasons.
     *
     * @param int $operationMemberId The ID of the operation member
     * @param array<array{id: int, reason: string}> $rejectedAttachments The list of attachments to reject with reasons
     * @return void
     * @throws ApiException If the API request fails
     */
    public function rejectAttachments(int $operationMemberId, array $rejectedAttachments): void
    {
        if ($operationMemberId <= 0) {
            throw new \InvalidArgumentException('Operation member ID must be greater than zero.');
        }
        
        if (empty($rejectedAttachments)) {
            throw new \InvalidArgumentException('Rejected attachments cannot be empty.');
        }
        
        foreach ($rejectedAttachments as $attachment) {
            if (!isset($attachment['id'], $attachment['reason']) || empty($attachment['reason'])) {
                throw new \InvalidArgumentException('Each rejected attachment must have an ID and a non-empty reason.');
            }
        }
        
        $this->client->request('POST', '/api/v2/attachment/reject', [
            'json' => [
                'operationMemberId' => $operationMemberId,
                'rejectedAttachments' => $rejectedAttachments,
            ],
        ]);
    }
    
    /**
     * Downloads an attachment by its ID.
     *
     * @param int $attachmentId The ID of the attachment to download
     * @return AttachmentDownloadResponse The attachment content and metadata
     * @throws ApiException If the API request fails
     */
    public function downloadAttachment(int $attachmentId): AttachmentDownloadResponse
    {
        if ($attachmentId <= 0) {
            throw new \InvalidArgumentException('Attachment ID must be greater than zero.');
        }
        
        $response = $this->client->request('GET', "/api/v2/attachment/{$attachmentId}/download");
        
        // The response is binary data, so we need to handle it differently
        // This is a placeholder for now, as we need to implement the actual binary data handling
        return new AttachmentDownloadResponse($response);
    }
}
