<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Интерфйс PromptInterface
 *
 * @package src/interfaces
 */
interface PromptInterface
{
    public function getSchema(): string;

    public function getPath(): string;

    public function getMethod(): string;

    public function getContentType(): ?string;

    public function isPrivate(): bool;

    public function getHeaders(): array;
}