<?php

namespace FelipeChiodini\LaravelCloudWatch;

use Countable;
use JsonSerializable;
use Stringable;

class Message implements Stringable, Countable, JsonSerializable
{
    protected $value;
    protected $timestamp;

    public function __construct(string $value, $timestamp)
    {
        $this->value = $value;
        $this->timestamp = $timestamp;
    }

    public function count(): int
    {
        return strlen($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'timestamp' => $this->timestamp
        ];
    }
}