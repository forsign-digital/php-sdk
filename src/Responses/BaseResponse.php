<?php

declare(strict_types=1);

namespace ForSign\Api\Responses;

/**
 * Base class for API responses.
 */
abstract class BaseResponse implements \JsonSerializable
{
    /**
     * @var array<string, mixed> The raw response data
     */
    protected array $data;
    
    /**
     * @param array<string, mixed> $data The response data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * Gets the raw response data.
     *
     * @return array<string, mixed> The raw response data
     */
    public function getRawData(): array
    {
        return $this->data;
    }
    
    /**
     * Converts the response to an array.
     *
     * @return array<string, mixed> The response as an array
     */
    public function toArray(): array
    {
        return $this->data;
    }
    
    /**
     * Serializes the response to JSON.
     *
     * @return array<string, mixed> The response as an array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
