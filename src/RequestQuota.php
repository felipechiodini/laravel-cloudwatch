<?php

namespace FelipeChiodini\LaravelCloudWatch;

use DateTime;

class RequestQuota
{
    public $value;
    public $remaining;
    public $timestamp;

    public function __construct(int $value)
    {
        $this->value = $value;
        $this->remaining = $value;
        $this->timestamp = new DateTime();
    }

    public function decrement()
    {
        if ($this->remaining === 0) {
            throw new NoRequestAvaiable('No requests available');
        }

        $this->remaining = $this->remaining - 1;

        if ($this->timestamp->diff(new DateTime())->s > 0) {
            $this->timestamp = new DateTime();
            $this->remaining = $this->value;
        }
    }

    public function hasRemaining(): bool
    {
        return $this->remaining > 0;
    }

    public function reset()
    {
        $this->timestamp = new DateTime();
        $this->remaining = $this->value;
    }
}
