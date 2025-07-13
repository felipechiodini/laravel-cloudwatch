<?php

namespace FelipeChiodini\LaravelCloudWatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;

class Client
{
    protected $client;

    public function __construct(Config $config)
    {
        $this->client = new CloudWatchLogsClient((array) $config);
    }

    public function instance(): CloudWatchLogsClient
    {
        return $this->client;
    }

    public function __call($name, $arguments)
    {
        return $this->instance()->$name(...$arguments);
    }
}