<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Response for a created operation.
 */
class OperationCreatedResponse extends BaseResponse
{
    /**
     * Gets the operation ID.
     *
     * @return int The operation ID
     */
    public function getId(): int
    {
        return (int) ($this->data['id'] ?? 0);
    }
    
    /**
     * Gets the operation name.
     *
     * @return string The operation name
     */
    public function getName(): string
    {
        return (string) ($this->data['name'] ?? '');
    }
    
    /**
     * Gets the operation members.
     *
     * @return array<array{id: int, name: string, signUrl: string}> The operation members
     */
    public function getMembers(): array
    {
        return $this->data['members'] ?? [];
    }
    
    /**
     * Gets the operation observers.
     *
     * @return array<array{id: int, name: string}> The operation observers
     */
    public function getObservers(): array
    {
        return $this->data['observers'] ?? [];
    }
    
    /**
     * Gets a specific member by ID.
     *
     * @param int $memberId The member ID
     * @return array{id: int, name: string, signUrl: string}|null The member or null if not found
     */
    public function getMemberById(int $memberId): ?array
    {
        foreach ($this->getMembers() as $member) {
            if (isset($member['id']) && (int) $member['id'] === $memberId) {
                return $member;
            }
        }
        
        return null;
    }
    
    /**
     * Gets a specific member by name.
     *
     * @param string $memberName The member name
     * @return array{id: int, name: string, signUrl: string}|null The member or null if not found
     */
    public function getMemberByName(string $memberName): ?array
    {
        foreach ($this->getMembers() as $member) {
            if (isset($member['name']) && $member['name'] === $memberName) {
                return $member;
            }
        }
        
        return null;
    }
    
    /**
     * Gets a specific observer by ID.
     *
     * @param int $observerId The observer ID
     * @return array{id: int, name: string}|null The observer or null if not found
     */
    public function getObserverById(int $observerId): ?array
    {
        foreach ($this->getObservers() as $observer) {
            if (isset($observer['id']) && (int) $observer['id'] === $observerId) {
                return $observer;
            }
        }
        
        return null;
    }
    
    /**
     * Gets a specific observer by name.
     *
     * @param string $observerName The observer name
     * @return array{id: int, name: string}|null The observer or null if not found
     */
    public function getObserverByName(string $observerName): ?array
    {
        foreach ($this->getObservers() as $observer) {
            if (isset($observer['name']) && $observer['name'] === $observerName) {
                return $observer;
            }
        }
        
        return null;
    }
    
    /**
     * Gets the signing URL for a specific member.
     *
     * @param int $memberId The member ID
     * @return string|null The signing URL or null if the member is not found
     */
    public function getSigningUrl(int $memberId): ?string
    {
        $member = $this->getMemberById($memberId);
        
        return $member['signUrl'] ?? null;
    }
}
