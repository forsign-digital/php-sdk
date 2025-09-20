<?php

declare(strict_types=1);

namespace ForSign\Api\Http;

/**
 * Options for HTTP requests.
 */
class RequestOptions
{
    /**
     * @var array<string, string> HTTP headers
     */
    private array $headers = [];
    
    /**
     * @var mixed|null Request body
     */
    private $body = null;
    
    /**
     * @var array<string, mixed> Query parameters
     */
    private array $query = [];
    
    /**
     * @var array<string, mixed> Form parameters
     */
    private array $form = [];
    
    /**
     * @var array<string, mixed> JSON parameters
     */
    private array $json = [];
    
    /**
     * @var array<string, mixed> Multipart form data
     */
    private array $multipart = [];
    
    /**
     * @var int Timeout in seconds
     */
    private int $timeout = 30;
    
    /**
     * @var bool Whether to verify SSL certificates
     */
    private bool $verify = true;
    
    /**
     * @var string|null Path to a custom CA bundle
     */
    private ?string $caBundle = null;
    
    /**
     * @var array<string, mixed> Proxy configuration
     */
    private array $proxy = [];
    
    /**
     * @var bool Whether to allow redirects
     */
    private bool $allowRedirects = true;
    
    /**
     * @var int Maximum number of redirects
     */
    private int $maxRedirects = 5;
    
    /**
     * @var string|null Idempotency key
     */
    private ?string $idempotencyKey = null;
    
    /**
     * Sets an HTTP header.
     *
     * @param string $name Header name
     * @param string $value Header value
     * @return self
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }
    
    /**
     * Sets multiple HTTP headers.
     *
     * @param array<string, string> $headers Headers
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }
        
        return $this;
    }
    
    /**
     * Sets the request body.
     *
     * @param mixed $body Request body
     * @return self
     */
    public function withBody($body): self
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * Sets a query parameter.
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @return self
     */
    public function withQueryParam(string $name, $value): self
    {
        $this->query[$name] = $value;
        return $this;
    }
    
    /**
     * Sets multiple query parameters.
     *
     * @param array<string, mixed> $params Parameters
     * @return self
     */
    public function withQueryParams(array $params): self
    {
        foreach ($params as $name => $value) {
            $this->withQueryParam($name, $value);
        }
        
        return $this;
    }
    
    /**
     * Sets a form parameter.
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @return self
     */
    public function withFormParam(string $name, $value): self
    {
        $this->form[$name] = $value;
        return $this;
    }
    
    /**
     * Sets multiple form parameters.
     *
     * @param array<string, mixed> $params Parameters
     * @return self
     */
    public function withFormParams(array $params): self
    {
        foreach ($params as $name => $value) {
            $this->withFormParam($name, $value);
        }
        
        return $this;
    }
    
    /**
     * Sets a JSON parameter.
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @return self
     */
    public function withJsonParam(string $name, $value): self
    {
        $this->json[$name] = $value;
        return $this;
    }
    
    /**
     * Sets multiple JSON parameters.
     *
     * @param array<string, mixed> $params Parameters
     * @return self
     */
    public function withJsonParams(array $params): self
    {
        foreach ($params as $name => $value) {
            $this->withJsonParam($name, $value);
        }
        
        return $this;
    }
    
    /**
     * Sets the JSON body.
     *
     * @param array<string, mixed> $json JSON body
     * @return self
     */
    public function withJson(array $json): self
    {
        $this->json = $json;
        return $this;
    }
    
    /**
     * Adds a multipart form data field.
     *
     * @param string $name Field name
     * @param mixed $contents Field contents
     * @param array<string, mixed> $headers Field headers
     * @return self
     */
    public function withMultipartField(string $name, $contents, array $headers = []): self
    {
        $this->multipart[] = [
            'name' => $name,
            'contents' => $contents,
            'headers' => $headers,
        ];
        
        return $this;
    }
    
    /**
     * Sets the timeout.
     *
     * @param int $timeout Timeout in seconds
     * @return self
     */
    public function withTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }
    
    /**
     * Sets whether to verify SSL certificates.
     *
     * @param bool $verify Whether to verify SSL certificates
     * @return self
     */
    public function withVerify(bool $verify): self
    {
        $this->verify = $verify;
        return $this;
    }
    
    /**
     * Sets the path to a custom CA bundle.
     *
     * @param string $caBundle Path to a custom CA bundle
     * @return self
     */
    public function withCaBundle(string $caBundle): self
    {
        $this->caBundle = $caBundle;
        return $this;
    }
    
    /**
     * Sets the proxy configuration.
     *
     * @param array<string, mixed> $proxy Proxy configuration
     * @return self
     */
    public function withProxy(array $proxy): self
    {
        $this->proxy = $proxy;
        return $this;
    }
    
    /**
     * Sets whether to allow redirects.
     *
     * @param bool $allowRedirects Whether to allow redirects
     * @return self
     */
    public function withAllowRedirects(bool $allowRedirects): self
    {
        $this->allowRedirects = $allowRedirects;
        return $this;
    }
    
    /**
     * Sets the maximum number of redirects.
     *
     * @param int $maxRedirects Maximum number of redirects
     * @return self
     */
    public function withMaxRedirects(int $maxRedirects): self
    {
        $this->maxRedirects = $maxRedirects;
        return $this;
    }
    
    /**
     * Sets the idempotency key.
     *
     * @param string $idempotencyKey Idempotency key
     * @return self
     */
    public function withIdempotencyKey(string $idempotencyKey): self
    {
        $this->idempotencyKey = $idempotencyKey;
        return $this;
    }
    
    /**
     * Converts the options to an array for Guzzle.
     *
     * @return array<string, mixed> The options as an array
     */
    public function toArray(): array
    {
        $options = [
            'timeout' => $this->timeout,
            'verify' => $this->verify,
            'allow_redirects' => $this->allowRedirects ? [
                'max' => $this->maxRedirects,
            ] : false,
        ];
        
        if (!empty($this->headers)) {
            $options['headers'] = $this->headers;
        }
        
        if ($this->idempotencyKey !== null) {
            $options['headers']['Idempotency-Key'] = $this->idempotencyKey;
        }
        
        if ($this->body !== null) {
            $options['body'] = $this->body;
        }
        
        if (!empty($this->query)) {
            $options['query'] = $this->query;
        }
        
        if (!empty($this->form)) {
            $options['form_params'] = $this->form;
        }
        
        if (!empty($this->json)) {
            $options['json'] = $this->json;
        }
        
        if (!empty($this->multipart)) {
            $options['multipart'] = $this->multipart;
        }
        
        if ($this->caBundle !== null) {
            $options['verify'] = $this->caBundle;
        }
        
        if (!empty($this->proxy)) {
            $options['proxy'] = $this->proxy;
        }
        
        return $options;
    }
}
