<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

/**
 * Metadata for an operation.
 */
class Metadata implements \JsonSerializable
{
    /**
     * @var string The key of the metadata
     */
    private string $key;
    
    /**
     * @var string The value of the metadata
     */
    private string $value;
    
    /**
     * @param string $key The key of the metadata
     * @param string $value The value of the metadata
     */
    public function __construct(string $key, string $value)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Metadata key cannot be empty.');
        }
        
        $this->key = $key;
        $this->value = $value;
    }
    
    /**
     * Gets the key of the metadata.
     *
     * @return string The key
     */
    public function getKey(): string
    {
        return $this->key;
    }
    
    /**
     * Sets the key of the metadata.
     *
     * @param string $key The key
     * @return self
     */
    public function setKey(string $key): self
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Metadata key cannot be empty.');
        }
        
        $this->key = $key;
        return $this;
    }
    
    /**
     * Gets the value of the metadata.
     *
     * @return string The value
     */
    public function getValue(): string
    {
        return $this->value;
    }
    
    /**
     * Sets the value of the metadata.
     *
     * @param string $value The value
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Converts the metadata to an array.
     *
     * @return array<string, string> The metadata as an array
     */
    public function toArray(): array
    {
        return [
            'Key' => $this->key,
            'Value' => $this->value,
        ];
    }
    
    /**
     * Serializes the metadata to JSON.
     *
     * @return array<string, string> The metadata as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
