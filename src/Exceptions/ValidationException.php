<?php

declare(strict_types=1);

namespace ForSign\Api\Exceptions;

/**
 * Exception thrown when validation errors occur.
 */
class ValidationException extends ApiException
{
    /**
     * @param string $message The exception message
     * @param int $statusCode The HTTP status code
     * @param \Throwable|null $previous The previous exception
     * @param array|null $messages Validation error messages from the API
     * @param string|null $requestId The request ID for tracking
     */
    public function __construct(
        string $message,
        int $statusCode = 422,
        ?\Throwable $previous = null,
        ?array $messages = null,
        ?string $requestId = null
    ) {
        parent::__construct($message, $statusCode, $previous, $messages, $requestId);
    }
    
    /**
     * Gets the validation errors as an associative array.
     *
     * @return array<string, string> The validation errors
     */
    public function getValidationErrors(): array
    {
        $errors = [];
        $messages = $this->getMessages();
        
        if ($messages === null) {
            return $errors;
        }
        
        foreach ($messages as $message) {
            if (isset($message['key'], $message['value'])) {
                $errors[$message['key']] = $message['value'];
            }
        }
        
        return $errors;
    }
    
    /**
     * Checks if there is a validation error for a specific field.
     *
     * @param string $field The field name
     * @return bool True if there is a validation error for the field
     */
    public function hasErrorFor(string $field): bool
    {
        return isset($this->getValidationErrors()[$field]);
    }
    
    /**
     * Gets the validation error message for a specific field.
     *
     * @param string $field The field name
     * @return string|null The error message or null if there is no error for the field
     */
    public function getErrorFor(string $field): ?string
    {
        return $this->getValidationErrors()[$field] ?? null;
    }
}
