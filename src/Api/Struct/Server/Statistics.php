<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

class Statistics extends \PleskX\Api\Struct
{
    /** @var Statistics\Objects */
    public $objects;

    /** @var Statistics\Version */
    public $version;

    /** @var Statistics\Other */
    public $other;

    /** @var Statistics\LoadAverage */
    public $loadAverage;

    /** @var Statistics\Memory */
    public $memory;

    /** @var Statistics\Swap */
    public $swap;

    /** @var Statistics\DiskSpace[] */
    public $diskSpace;

    public function __construct($apiResponse)
    {
        $this->objects = new Statistics\Objects($apiResponse->objects);
        $this->version = new Statistics\Version($apiResponse->version);
        $this->other = new Statistics\Other($apiResponse->other);
        $this->loadAverage = new Statistics\LoadAverage($apiResponse->load_avg);
        $this->memory = new Statistics\Memory($apiResponse->mem);
        $this->swap = new Statistics\Swap($apiResponse->swap);

        $this->diskSpace = [];
        foreach ($apiResponse->diskspace as $disk) {
            $this->diskSpace[(string) $disk->device->name] = new Statistics\DiskSpace($disk->device);
        }
    }
}
