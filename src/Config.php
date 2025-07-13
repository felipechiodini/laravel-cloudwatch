<?php

namespace FelipeChiodini\LaravelCloudWatch;

use JsonSerializable;

class Config implements JsonSerializable
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function jsonSerialize(): array
    {
        return [
            'version' => $this->config['version'],
            'region'  => $this->config['region'],
            'credentials' => [
                'key'    => $this->config['key'],
                'secret' => $this->config['secret']
            ]
        ];
    }
}