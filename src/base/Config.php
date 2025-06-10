<?php

namespace andy87\sdk\client\base;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Base configuration value object for API clients.
 * Holds various settings and dependencies such as base URI, headers, timeouts, and integrations.
 */
abstract class Config
{
    /**
     * Base URI for API requests (e.g. "https://api.example.com").
     */
    public string $baseUri;

    /**
     * Prefix for API endpoints (appended to baseUri, e.g. "v1").
     */
    public string $prefix = '';

    /**
     * Default headers to include with every request.
     */
    public array $headers = [];

    /**
     * Connection timeout in seconds.
     */
    public int $connectTimeout = 0;

    /**
     * Response read timeout in seconds.
     */
    public int $readTimeout = 0;

    /**
     * PSR-18 HTTP client to send requests.
     */
    public ?ClientInterface $httpClient = null;

    /**
     * PSR-17 HTTP request factory.
     */
    public ?RequestFactoryInterface $requestFactory = null;

    /**
     * PSR-17 stream factory for request bodies.
     */
    public ?StreamFactoryInterface $streamFactory = null;

    /**
     * PSR-17 HTTP response factory.
     */
    public ?ResponseFactoryInterface $responseFactory = null;

    /**
     * PSR-6 cache pool for caching tokens or responses.
     */
    public ?CacheItemPoolInterface $cache = null;

    /**
     * PSR-11 container for dependency retrieval (optional).
     */
    public ?ContainerInterface $container = null;

    /**
     * PSR-3 logger for logging requests and responses (optional).
     */
    public ?LoggerInterface $logger = null;

    /**
     * Logging level for request/response logging.
     */
    public string $logLevel = LogLevel::INFO;

    /**
     * Error handler callable to handle exceptions (optional).
     * Signature: function(Exception $exception, \Psr\Http\Message\RequestInterface $request): ?\BaseClient\ResponseInterface
     */
    public $errorHandler = null;

    /**
     * Optional test case instance for verifying client behavior.
     */
    public ?BaseTestCase $testCase = null;

    /**
     * Constructs a new BaseConfig.
     * @param string $baseUri Base API URI.
     * @param string $prefix API endpoints prefix (if any).
     */
    public function __construct(string $baseUri = '', string $prefix = '')
    {
        $this->baseUri = rtrim($baseUri, '/');
        $this->prefix = trim($prefix, '/');
        // Initialize default headers array if not already set.
        if (empty($this->headers)) {
            $this->headers = [];
        }
    }

    /**
     * Gets the full base URI with prefix.
     * @return string Full base URI (including prefix if set).
     */
    public function getBaseUri(): string
    {
        if ($this->prefix !== '') {
            return "{$this->baseUri}/{$this->prefix}";
        }
        return $this->baseUri;
    }

    /**
     * Merge additional headers into default headers.
     * @param array $headers Headers to add or override.
     * @return void
     */
    public function addHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }
}
