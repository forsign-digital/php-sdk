<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

/**
 * Request for an operation document.
 */
class OperationDocumentRequest implements \JsonSerializable
{
    /**
     * @var string The ID of the document
     */
    private string $id;
    
    /**
     * @var string|null The description of the document
     */
    private ?string $description;
    
    /**
     * @param string $id The ID of the document
     * @param string|null $description The description of the document
     */
    public function __construct(string $id, ?string $description = null)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Document ID cannot be empty.');
        }
        
        $this->id = $id;
        $this->description = $description;
    }
    
    /**
     * Gets the ID of the document.
     *
     * @return string The ID
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Sets the ID of the document.
     *
     * @param string $id The ID
     * @return self
     */
    public function setId(string $id): self
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Document ID cannot be empty.');
        }
        
        $this->id = $id;
        return $this;
    }
    
    /**
     * Gets the description of the document.
     *
     * @return string|null The description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * Sets the description of the document.
     *
     * @param string|null $description The description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
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
            'Id' => $this->id,
        ];
        
        if ($this->description !== null) {
            $data['Description'] = $this->description;
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
    
    /**
     * Checks if the document is equal to another document.
     *
     * @param mixed $obj The object to compare with
     * @return bool True if the documents are equal, false otherwise
     */
    public function equals(mixed $obj): bool
    {
        if (!$obj instanceof self) {
            return false;
        }
        
        return $this->id === $obj->id;
    }
}
