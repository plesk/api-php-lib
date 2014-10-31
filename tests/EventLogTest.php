<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class EventLogTest extends TestCase
{

    public function testGet()
    {
        $events = $this->_client->eventLog()->get();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetDetailedLog()
    {
        $events = $this->_client->eventLog()->getDetailedLog();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
        $this->assertGreaterThan(0, strlen($event->user));
    }

    public function testGetLastId()
    {
        $lastId = $this->_client->eventLog()->getLastId();
        $this->assertGreaterThan(0, $lastId);
    }

}
