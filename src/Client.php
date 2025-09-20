<?php

declare(strict_types=1);

namespace ForSign\Api;

use ForSign\Api\Auth\ApiKeyCredential;
use ForSign\Api\Auth\CredentialInterface;
use ForSign\Api\Builders\OperationBuilder;
use ForSign\Api\Exceptions\ApiException;
use ForSign\Api\Responses\DocumentUploadResponse;
use ForSign\Api\Exceptions\ValidationException;
use ForSign\Api\Repositories\OperationRepository;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use GuzzleHttp\Psr7\Utils;

/**
 * Client for interacting with the ForSign API.
 */
class Client
{
    private const DEFAULT_BASE_URI = 'https://api.forsign.digital';
    private const DEFAULT_TIMEOUT = 30.0;

    private HttpClient $httpClient;
    private ?CredentialInterface $credential = null;
    private string $correlationId;
    private LoggerInterface $logger;
    private ?OperationRepository $operationRepository = null;

    /**
     * @param string|null $apiKey API key for authentication
     * @param array $options Configuration options
     * @param LoggerInterface|null $logger PSR-3 logger
     */
    public function __construct(
        ?string $apiKey = null,
        array $options = [],
        ?LoggerInterface $logger = null
    ) {
        $this->correlationId = $this->generateCorrelationId();
        $this->logger = $logger ?? new NullLogger();

        $baseUri = $options['baseUri'] ?? self::DEFAULT_BASE_URI;
        $timeout = $options['timeout'] ?? self::DEFAULT_TIMEOUT;

        $this->httpClient = new HttpClient([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
            'http_errors' => false, // We'll handle errors ourselves
        ]);

        if ($apiKey !== null) {
            $this->setCredential(new ApiKeyCredential($apiKey));
        }
    }

    /**
     * Sets the credential for authentication.
     *
     * @param CredentialInterface $credential
     * @return self
     */
    public function setCredential(CredentialInterface $credential): self
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * Gets standard headers for API requests.
     *
     * @return array
     */
    private function getStandardHeaders(): array
    {
        $headers = [
            'User-Agent' => 'ForSignPhpClient/2.0',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($this->credential !== null) {
            $headers = array_merge($headers, $this->credential->getAuthorizationHeader());
        } else {
             throw new ApiException('Credential must be set before making API calls.');
        }

        return $headers;
    }

    /**
     * Parses a response into an array.
     *
     * @param ResponseInterface $response
     * @return array
     * @throws ApiException If the response cannot be parsed
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        if (empty($body)) {
            return [];
        }

        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Failed to parse response: ' . json_last_error_msg(),
                $response->getStatusCode()
            );
        }

        return $data;
    }

    /**
     * Generates a correlation ID for request tracking.
     *
     * @return string
     */
    private function generateCorrelationId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }

    /**
     * Gets the operation repository.
     *
     * @return OperationRepository
     */
    public function operations(): OperationRepository
    {
        if ($this->operationRepository === null) {
            $this->operationRepository = new OperationRepository($this);
        }

        return $this->operationRepository;
    }

    /**
     * Executes a request to the ForSign API.
     *
     * @param string $method HTTP method
     * @param string $uri URI
     * @param array $options Request options
     * @return array Response data
     * @throws ApiException If the request fails
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        $options['headers'] = array_merge($this->getStandardHeaders(), $options['headers'] ?? []);
        // Correlation-Id por request (o caller pode sobrescrever)
        $options['headers']['X-Correlation-Id'] = $options['headers']['X-Correlation-Id'] ?? $this->generateCorrelationId();

        // multipart: remova Content-Type padrão
        if (isset($options['multipart'])) {
            unset($options['headers']['Content-Type']);
        }

        try {
            $response   = $this->httpClient->request($method, $uri, $options);
            $statusCode = $response->getStatusCode();
            $rawBody    = (string) $response->getBody();
            $body       = $this->parseResponse($response); // pode virar ['_raw' => ...] se não for JSON

            if ($statusCode >= 400) {
                $messages = $body['messages'] ?? $body['errors'] ?? $body['error'] ?? null;
                $raw      = $body['_raw'] ?? $rawBody; // sempre tenha um fallback cru
                $snippet  = mb_strimwidth(trim($raw), 0, 800, '…'); // limite para log/exceção

                $errorMessage = $this->getErrorMessageForStatusCode($statusCode);
                if ($messages && is_array($messages)) {
                    // concatena mensagens comuns de validação (422/400)
                    $joined = implode(' | ', array_map('strval', is_array($messages) ? $messages : [$messages]));
                    $errorMessage .= " — Details: {$joined}";
                } elseif (!empty($snippet)) {
                    $errorMessage .= " — Body: {$snippet}";
                }

                $this->logger->error('API request returned error', [
                    'status'   => $statusCode,
                    'method'   => $method,
                    'uri'      => $uri,
                    'headers'  => array_diff_key($options['headers'], ['Authorization' => true]), // não loga o token
                    'body'     => isset($options['json']) ? $options['json'] : (isset($options['multipart']) ? '[multipart]' : null),
                    'response_headers' => $response->getHeaders(),
                    'response_snippet' => $snippet,
                ]);

                if ($statusCode === 422 && $messages !== null) {
                    throw new ValidationException($errorMessage, $statusCode, null, $messages);
                }
                throw new ApiException($errorMessage, $statusCode);
            }

            return $body;
        } catch (GuzzleException $e) {
            $this->logger->error('API request failed', ['method' => $method, 'uri' => $uri, 'exception' => $e]);
            throw new ApiException('API request failed: ' . $e->getMessage(), 0, $e);
        }
    }



    /**
     * Gets a descriptive error message based on the HTTP status code.
     *
     * @param int $statusCode
     * @return string
     */
    private function getErrorMessageForStatusCode(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad request. The request was invalid or cannot be processed.',
            401 => 'Authentication failed. Please check your API key.',
            402 => 'Insufficient credits. Your account does not have enough credits to perform this operation.',
            403 => 'You don\'t have permission to perform this operation.',
            404 => 'Resource not found.',
            422 => 'Validation error. The request contains invalid data.',
            429 => 'Too many requests. You have exceeded the rate limit.',
            500 => 'An internal server error occurred.',
            default => "Error processing request: HTTP $statusCode",
        };
    }

    /**
     * Creates a new OperationBuilder to fluently construct an operation request.
     *
     * @param string $operationName The name of the operation.
     * @return OperationBuilder A new builder instance.
     */
    public function createOperationBuilder(string $operationName): OperationBuilder
    {
        return OperationBuilder::initializeWithName($operationName);
    }

    /**
     * Uploads a file to the ForSign platform.
     *
     * @param string $filePath The path to the file to be uploaded. Must be a PDF.
     * @return DocumentUploadResponse The response containing the uploaded document details.
     * @throws ApiException if the API request fails or the file is invalid.
     * @throws \InvalidArgumentException if the file does not exist or is not a PDF.
     */
    public function uploadFile(string $filePath): DocumentUploadResponse
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found at path: {$filePath}");
        }

        // Validação de extensão (rápida) + MIME (confiável)
        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'pdf') {
            throw new \InvalidArgumentException('Only PDF files are supported.');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if ($finfo->file($filePath) !== 'application/pdf') {
            throw new \InvalidArgumentException('Only PDF files are supported.');
        }

        // Abre o arquivo em modo binário com tratamento de erro
        $stream = Utils::tryFopen($filePath, 'rb');

        $options = [
            'headers' => $this->getStandardHeaders(),
            'multipart' => [
                [
                    'name'     => 'file', // confirme com o backend
                    'contents' => $stream,
                    'filename' => basename($filePath),
                    // opcional: 'headers' => ['Content-Type' => 'application/pdf'],
                ],
            ],
            'timeout' => 30,
            'connect_timeout' => 10,
            // 'http_errors' => true, // padrão; mantenha assim para lançar exceções em 4xx/5xx
        ];

        // Garanta que não há Content-Type fixo atrapalhando o multipart
        if (isset($options['headers']['Content-Type'])) {
            unset($options['headers']['Content-Type']);
        }

        try {
            $response = $this->httpClient->request('POST', '/api/v2/document/upload', $options);
            $body = $this->parseResponse($response);

            return new DocumentUploadResponse($body);
        } catch (GuzzleException $e) {
            $this->logger->error('File upload failed', [
                'path' => $filePath,
                'exception' => $e,
                'code' => method_exists($e, 'getCode') ? $e->getCode() : null,
            ]);
            throw new ApiException('File upload failed: ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}