<?php

namespace andy87\sdk\client\core\transport;

/**
 * Class Path
 *
 * Представляет путь к ресурсу, включая протокол, хост, порт, префикс и путь.
 *
 * @package src/core/transport
 */
class Url
{
    /** @var string $protocol Протокол*/
    protected string $protocol;

    /** @var string $host Хост */
    protected string $host;

    /** @var ?int $port Порт */
    protected ?int $port = null;

    /** @var ?string $prefix префикс `endpoint'a` добавляемый после `host`  */
    protected ?string $prefix = null;

    /** @var ?string $path Путь к ресурсу, добавляемый после префикса */
    protected ?string $path = null;



    /**
     * Path constructor.
     *
     * @param string $protocol
     * @param string $host
     * @param ?int $port
     * @param ?string $prefix
     * @param ?string $path
     */
    public function __construct(string $protocol, string $host, ?int $port = null, ?string $prefix = null, ?string $path = null )
    {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->prefix = $prefix;
        $this->path = $path;
    }

    /**
     * Возвращает полный путь в виде строки.
     *
     * @return string
     */
    public function getFullPath(): string
    {
        $fullPath = $this->protocol . '://' . $this->host;

        if ($this->port !== null) {
            $fullPath .= ':' . $this->port;
        }

        if ($this->prefix !== null) {
            $fullPath .= '/' . trim($this->prefix, '/');
        }

        if ($this->path !== null) {
            $fullPath .= '/' . trim($this->path, '/');
        }

        return $fullPath;
    }

    /**
     * Возвращает протокол.
     *
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Возвращает хост.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Возвращает порт.
     *
     * @return ?int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Возвращает префикс.
     *
     * @return ?string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Возвращает путь.
     *
     * @return ?string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }
}