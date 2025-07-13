<?php

namespace FelipeChiodini\LaravelCloudWatch;

use Illuminate\Support\Collection;

class Buffer
{
    protected $items;
    protected $sizeLimit;
    protected $currentSize = 0;

    public function __construct(int $sizeLimit)
    {
        $this->items = collect([]);
        $this->sizeLimit = $sizeLimit;
    }

    public function push(Message $message, callable $whenReachLimit): void
    {
        if ($this->currentSize + sizeof($message) >= $this->sizeLimit) {
            $whenReachLimit($this->items);
            $this->currentSize = 0;
            $this->items = collect([]);
        }

        $this->currentSize = $this->currentSize + sizeof($message);
        $this->items->push($message);
    }

    public function items(): Collection
    {
        return $this->items;
    }
}