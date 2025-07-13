<?php

namespace FelipeChiodini\LaravelCloudWatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Support\Collection;

class CloudWatchService
{
    protected $client;
    protected $group;
    protected $stream;
    protected $buffer;
    protected $nextTokenSequence;
    protected $requestQuota;

    public function __construct(CloudWatchLogsClient $client)
    {
        $this->client = $client;
        $this->buffer = new Buffer(262118);
        $this->requestQuota = new RequestQuota(5);
    }

    public function add($message): void
    {
        $this->buffer->push($message, function (Collection $items) {
            $this->send($items);
        });
    }

    public function send(Collection $items)
    {
        $data = [
            'logGroupName' => $this->group,
            'logStreamName' => $this->stream,
            'logEvents' => $items
                ->map(function (Message $item) { return $item->jsonSerialize(); })
                ->toArray(),
        ];

        if ($this->nextTokenSequence) {
            $data['nextSequenceToken'] = $this->nextTokenSequence;
        }

        while ($this->requestQuota->hasRemaining()) {
            $response = $this->client->createLogStream($data);

            $this->nextTokenSequence = $response->get('nextSequenceToken');

            $this->requestQuota->decrement();
        }
    }

    public function setGroup(string $group)
    {
        $this->group = $group;
    }

    public function setStream(string $stream)
    {
        $this->stream = $stream;
    }

    public function dwa()
    {
        $result = $this->client->describeLogStreams([
            'logGroupName' => $this->group,
            'logStreamNamePrefix' => $this->stream,
        ]);
    }

    public function __destruct()
    {
        // $this->buffer->flush();
    }
}