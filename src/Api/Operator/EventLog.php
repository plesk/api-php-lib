<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\EventLog as Struct;

class EventLog extends \PleskX\Api\Operator
{
    protected string $wrapperTag = 'event_log';

    /**
     * @return Struct\Event[]
     */
    public function get(): array
    {
        $records = [];
        $response = $this->request('get');

        foreach ($response->event as $eventInfo) {
            $records[] = new Struct\Event($eventInfo);
        }

        return $records;
    }

    /**
     * @return Struct\DetailedEvent[]
     */
    public function getDetailedLog(): array
    {
        $records = [];
        $response = $this->request('get_events');

        foreach ($response->event as $eventInfo) {
            $records[] = new Struct\DetailedEvent($eventInfo);
        }

        return $records;
    }

    public function getLastId(): int
    {
        return (int) $this->request('get-last-id')->getValue('id');
    }
}
