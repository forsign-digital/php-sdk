<?php

declare(strict_types=1);

namespace ForSign\Api\Auth;

/**
 * Interface for credentials used to authenticate with the ForSign API.
 */
interface CredentialInterface
{
    /**
     * Gets the authorization header for API requests.
     *
     * @return array<string, string> The authorization header
     */
    public function getAuthorizationHeader(): array;
    
    /**
     * Converts the credential to an array for API requests.
     *
     * @return array The credential as an array
     */
    public function toArray(): array;
}
