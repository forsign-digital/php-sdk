<?php

declare(strict_types=1);

namespace ForSign\Api\Exceptions;

/**
 * Exception thrown when an API request fails.
 */
class ApiException extends \Exception
{
    private int $statusCode;
    private ?string $requestId;
    private ?array $messages;
    
    /**
     * @param string $message The exception message
     * @param int $statusCode The HTTP status code
     * @param \Throwable|null $previous The previous exception
     * @param array|null $messages Additional error messages from the API
     * @param string|null $requestId The request ID for tracking
     */
    public function __construct(
        string $message,
        int $statusCode = 0,
        ?\Throwable $previous = null,
        ?array $messages = null,
        ?string $requestId = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;
        $this->messages = $messages;
    }
    
    /**
     * Gets the HTTP status code.
     *
     * @return int The HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Gets the request ID.
     *
     * @return string|null The request ID
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
    
    /**
     * Gets the additional error messages from the API.
     *
     * @return array|null The error messages
     */
    public function getMessages(): ?array
    {
        return $this->messages;
    }
    
    /**
     * Gets a formatted string representation of the error messages.
     *
     * @return string|null The formatted error messages
     */
    public function getFormattedMessages(): ?string
    {
        if ($this->messages === null) {
            return null;
        }
        
        $formatted = [];
        foreach ($this->messages as $message) {
            if (isset($message['key'], $message['value'])) {
                $formatted[] = "{$message['key']}: {$message['value']}";
            } elseif (is_string($message)) {
                $formatted[] = $message;
            }
        }
        
        return !empty($formatted) ? implode(PHP_EOL, $formatted) : null;
    }
}
