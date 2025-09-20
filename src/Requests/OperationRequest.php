<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

use ForSign\Api\Enums\Language;

/**
 * Request for creating an operation.
 */
class OperationRequest implements \JsonSerializable
{
    /**
     * @var string The name of the operation
     */
    private string $name;
    
    /**
     * @var string The language for the operation's interface and notifications
     */
    private string $language = 'pt-br';
    
    /**
     * @var bool Whether to display a cover page before the signing process
     */
    private bool $displayCover = true;
    
    /**
     * @var array<OperationDocumentRequest> The files to be signed
     */
    private array $files = [];
    
    /**
     * @var array<OperationMemberRequest> The members of the operation
     */
    private array $members = [];
    
    /**
     * @var array<int> The groups to add the operation to
     */
    private array $groups = [];
    
    /**
     * @var string|null The optional message to include in the operation
     */
    private ?string $optionalMessage = null;
    
    /**
     * @var \DateTime|null The expiration date of the operation
     */
    private ?\DateTime $expirationDate = null;
    
    /**
     * @var string|null The external ID of the operation
     */
    private ?string $externalId = null;
    
    /**
     * @var bool Whether the signers' order is important
     */
    private bool $order = false;
    
    /**
     * @var bool Whether to enable notifications about member actions
     */
    private bool $memberMovementWarning = false;
    
    /**
     * @var bool Whether the signing will be done in person
     */
    private bool $onPremises = false;
    
    /**
     * @var int|null The ID of the operation model to use
     */
    private ?int $operationModelId = null;
    
    /**
     * @var ManualFinishRequest|null The manual finish configuration
     */
    private ?ManualFinishRequest $manualFinish = null;
    
    /**
     * @var array<Metadata> The metadata for the operation
     */
    private array $metadata = [];
    
    /**
     * @param string $name The name of the operation
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    /**
     * Gets the name of the operation.
     *
     * @return string The name of the operation
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Sets the name of the operation.
     *
     * @param string $name The name of the operation
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Gets the language for the operation's interface and notifications.
     *
     * @return string The language
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
    
    /**
     * Sets the language for the operation's interface and notifications.
     *
     * @param string $language The language
     * @return self
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * Gets whether to display a cover page before the signing process.
     *
     * @return bool Whether to display a cover page
     */
    public function getDisplayCover(): bool
    {
        return $this->displayCover;
    }
    
    /**
     * Sets whether to display a cover page before the signing process.
     *
     * @param bool $displayCover Whether to display a cover page
     * @return self
     */
    public function setDisplayCover(bool $displayCover): self
    {
        $this->displayCover = $displayCover;
        return $this;
    }
    
    /**
     * Gets the files to be signed.
     *
     * @return array<OperationDocumentRequest> The files
     */
    public function getFiles(): array
    {
        return $this->files;
    }
    
    /**
     * Sets the files to be signed.
     *
     * @param array<OperationDocumentRequest> $files The files
     * @return self
     */
    public function setFiles(array $files): self
    {
        $this->files = $files;
        return $this;
    }
    
    /**
     * Adds a file to be signed.
     *
     * @param OperationDocumentRequest $file The file
     * @return self
     */
    public function addFile(OperationDocumentRequest $file): self
    {
        $this->files[] = $file;
        return $this;
    }
    
    /**
     * Gets the members of the operation.
     *
     * @return array<OperationMemberRequest> The members
     */
    public function getMembers(): array
    {
        return $this->members;
    }
    
    /**
     * Sets the members of the operation.
     *
     * @param array<OperationMemberRequest> $members The members
     * @return self
     */
    public function setMembers(array $members): self
    {
        $this->members = $members;
        return $this;
    }
    
    /**
     * Adds a member to the operation.
     *
     * @param OperationMemberRequest $member The member
     * @return self
     */
    public function addMember(OperationMemberRequest $member): self
    {
        if ($this->order) {
            $member->setOrderPosition(count($this->members) + 1);
        } else {
            $member->setOrderPosition(0);
        }
        
        $this->members[] = $member;
        return $this;
    }
    
    /**
     * Gets the groups to add the operation to.
     *
     * @return array<int> The groups
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
    
    /**
     * Sets the groups to add the operation to.
     *
     * @param array<int> $groups The groups
     * @return self
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }
    
    /**
     * Adds a group to add the operation to.
     *
     * @param int $group The group
     * @return self
     */
    public function addGroup(int $group): self
    {
        $this->groups[] = $group;
        return $this;
    }
    
    /**
     * Gets the optional message to include in the operation.
     *
     * @return string|null The optional message
     */
    public function getOptionalMessage(): ?string
    {
        return $this->optionalMessage;
    }
    
    /**
     * Sets the optional message to include in the operation.
     *
     * @param string|null $optionalMessage The optional message
     * @return self
     */
    public function setOptionalMessage(?string $optionalMessage): self
    {
        $this->optionalMessage = $optionalMessage;
        return $this;
    }
    
    /**
     * Gets the expiration date of the operation.
     *
     * @return \DateTime|null The expiration date
     */
    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }
    
    /**
     * Sets the expiration date of the operation.
     *
     * @param \DateTime|null $expirationDate The expiration date
     * @return self
     */
    public function setExpirationDate(?\DateTime $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }
    
    /**
     * Gets the external ID of the operation.
     *
     * @return string|null The external ID
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
    
    /**
     * Sets the external ID of the operation.
     *
     * @param string|null $externalId The external ID
     * @return self
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }
    
    /**
     * Gets whether the signers' order is important.
     *
     * @return bool Whether the signers' order is important
     */
    public function getOrder(): bool
    {
        return $this->order;
    }
    
    /**
     * Sets whether the signers' order is important.
     *
     * @param bool $order Whether the signers' order is important
     * @return self
     */
    public function setOrder(bool $order): self
    {
        $this->order = $order;
        return $this;
    }
    
    /**
     * Gets whether to enable notifications about member actions.
     *
     * @return bool Whether to enable notifications about member actions
     */
    public function getMemberMovementWarning(): bool
    {
        return $this->memberMovementWarning;
    }
    
    /**
     * Sets whether to enable notifications about member actions.
     *
     * @param bool $memberMovementWarning Whether to enable notifications about member actions
     * @return self
     */
    public function setMemberMovementWarning(bool $memberMovementWarning): self
    {
        $this->memberMovementWarning = $memberMovementWarning;
        return $this;
    }
    
    /**
     * Gets whether the signing will be done in person.
     *
     * @return bool Whether the signing will be done in person
     */
    public function getOnPremises(): bool
    {
        return $this->onPremises;
    }
    
    /**
     * Sets whether the signing will be done in person.
     *
     * @param bool $onPremises Whether the signing will be done in person
     * @return self
     */
    public function setOnPremises(bool $onPremises): self
    {
        $this->onPremises = $onPremises;
        return $this;
    }
    
    /**
     * Gets the ID of the operation model to use.
     *
     * @return int|null The ID of the operation model
     */
    public function getOperationModelId(): ?int
    {
        return $this->operationModelId;
    }
    
    /**
     * Sets the ID of the operation model to use.
     *
     * @param int|null $operationModelId The ID of the operation model
     * @return self
     */
    public function setOperationModelId(?int $operationModelId): self
    {
        $this->operationModelId = $operationModelId;
        return $this;
    }
    
    /**
     * Gets the manual finish configuration.
     *
     * @return ManualFinishRequest|null The manual finish configuration
     */
    public function getManualFinish(): ?ManualFinishRequest
    {
        return $this->manualFinish;
    }
    
    /**
     * Sets the manual finish configuration.
     *
     * @param ManualFinishRequest|null $manualFinish The manual finish configuration
     * @return self
     */
    public function setManualFinish(?ManualFinishRequest $manualFinish): self
    {
        $this->manualFinish = $manualFinish;
        return $this;
    }
    
    /**
     * Sets whether the operation requires manual finish.
     *
     * @param bool $hasManualFinish Whether the operation requires manual finish
     * @return self
     */
    public function setHasManualFinish(bool $hasManualFinish): self
    {
        if ($this->manualFinish === null) {
            $this->manualFinish = new ManualFinishRequest();
        }
        
        $this->manualFinish->setHasManualFinish($hasManualFinish);
        
        if ($this->expirationDate !== null) {
            $this->manualFinish->setDate($this->expirationDate);
        }
        
        return $this;
    }
    
    /**
     * Gets the metadata for the operation.
     *
     * @return array<Metadata> The metadata
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Sets the metadata for the operation.
     *
     * @param array<Metadata> $metadata The metadata
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }
    
    /**
     * Adds a metadata entry to the operation.
     *
     * @param Metadata $metadata The metadata entry
     * @return self
     */
    public function addMetadata(Metadata $metadata): self
    {
        $this->metadata[] = $metadata;
        return $this;
    }
    
    /**
     * Adds a metadata entry to the operation.
     *
     * @param string $key The key of the metadata
     * @param string $value The value of the metadata
     * @return self
     */
    public function addMetadataEntry(string $key, string $value): self
    {
        $this->metadata[] = new Metadata($key, $value);
        return $this;
    }
    
    /**
     * Converts the request to an array.
     *
     * @return array<string, mixed> The request as an array
     */
    public function toArray(): array
    {
        $data = [
            'Name' => $this->name,
            'Language' => $this->language,
            'DisplayCover' => $this->displayCover,
            'Files' => array_map(fn($file) => $file->toArray(), $this->files),
            'Members' => array_map(fn($member) => $member->toArray(), $this->members),
            'Groups' => $this->groups,
            'Order' => $this->order,
            'MemberMovementWarning' => $this->memberMovementWarning,
            'OnPremises' => $this->onPremises,
            'Metadata' => array_map(fn($metadata) => $metadata->toArray(), $this->metadata),
        ];
        
        if ($this->optionalMessage !== null) {
            $data['OptionalMessage'] = $this->optionalMessage;
        }
        
        if ($this->expirationDate !== null) {
            $data['ExpirationDate'] = $this->expirationDate->format('c');
        }
        
        if ($this->externalId !== null) {
            $data['ExternalId'] = $this->externalId;
        }
        
        if ($this->operationModelId !== null) {
            $data['OperationModelId'] = $this->operationModelId;
        }
        
        if ($this->manualFinish !== null) {
            $data['ManualFinish'] = $this->manualFinish->toArray();
        }
        
        return $data;
    }
    
    /**
     * Serializes the request to JSON.
     *
     * @return array<string, mixed> The request as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
