<?php

namespace FelipeChiodini\LaravelCloudWatch;

class Builder
{
    private $cloudWatchService;

    public function __construct(CloudWatchService $cloudWatchService)
    {
        $this->cloudWatchService = $cloudWatchService;
    }

    public function log($message): self
    {
        $this->cloudWatchService->add($message);

        return $this;
    }

    public function multiple(array $messages): self
    {
        foreach ($messages as $message) {
            $this->log($message);
        }

        return $this;
    }

    public function group(string $name): self
    {
        $this->cloudWatchService->setGroup($name);

        return $this;
    }

    public function stream(string $name): self
    {
        $this->cloudWatchService->setStream($name);

        return $this;
    }
}