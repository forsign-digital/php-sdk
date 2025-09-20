<?php

declare(strict_types=1);

namespace ForSign\Api\Auth;

/**
 * Credential for authenticating with the ForSign API using an API key.
 */
class ApiKeyCredential implements CredentialInterface
{
    private string $apiKey;
    
    /**
     * @param string $apiKey The API key
     */
    public function __construct(string $apiKey)
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('API key cannot be empty');
        }
        
        $this->apiKey = $apiKey;
    }
    
    /**
     * Gets the authorization header for API requests.
     *
     * @return array<string, string> The authorization header
     */
    public function getAuthorizationHeader(): array
    {
        return [
            'X-Api-Key' => $this->apiKey,
        ];
    }
    
    /**
     * Converts the credential to an array for API requests.
     *
     * @return array The credential as an array
     */
    public function toArray(): array
    {
        return [
            'apiKey' => $this->apiKey,
        ];
    }
    
    /**
     * Gets the API key.
     *
     * @return string The API key
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
