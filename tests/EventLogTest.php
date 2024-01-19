<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class EventLogTest extends AbstractTestCase
{
    public function testGet()
    {
        $events = static::$client->eventLog()->get();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetDetailedLog()
    {
        $events = static::$client->eventLog()->getDetailedLog();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetLastId()
    {
        $lastId = static::$client->eventLog()->getLastId();
        $this->assertGreaterThan(0, $lastId);
    }
}
