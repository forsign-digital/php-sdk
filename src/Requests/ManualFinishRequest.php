<?php

declare(strict_types=1);

namespace ForSign\Api\Requests;

/**
 * Request for manual finish configuration.
 */
class ManualFinishRequest implements \JsonSerializable
{
    /**
     * @var bool Whether the operation requires manual finish
     */
    private bool $hasManualFinish = false;
    
    /**
     * @var \DateTime|null The date when the operation should be manually finished
     */
    private ?\DateTime $date = null;
    
    /**
     * @param bool $hasManualFinish Whether the operation requires manual finish
     * @param \DateTime|null $date The date when the operation should be manually finished
     */
    public function __construct(bool $hasManualFinish = false, ?\DateTime $date = null)
    {
        $this->hasManualFinish = $hasManualFinish;
        $this->date = $date;
    }
    
    /**
     * Gets whether the operation requires manual finish.
     *
     * @return bool Whether the operation requires manual finish
     */
    public function getHasManualFinish(): bool
    {
        return $this->hasManualFinish;
    }
    
    /**
     * Sets whether the operation requires manual finish.
     *
     * @param bool $hasManualFinish Whether the operation requires manual finish
     * @return self
     */
    public function setHasManualFinish(bool $hasManualFinish): self
    {
        $this->hasManualFinish = $hasManualFinish;
        return $this;
    }
    
    /**
     * Gets the date when the operation should be manually finished.
     *
     * @return \DateTime|null The date
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }
    
    /**
     * Sets the date when the operation should be manually finished.
     *
     * @param \DateTime|null $date The date
     * @return self
     */
    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;
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
            'HasManualFinish' => $this->hasManualFinish,
        ];
        
        if ($this->date !== null) {
            $data['Date'] = $this->date->format('c');
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
