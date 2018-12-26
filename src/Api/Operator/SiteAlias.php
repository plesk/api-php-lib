<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     * @param array $preferences
     * @return Struct\Info
     */
    public function create(array $properties, array $preferences = [])
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('create');

        if (count($preferences) > 0) {
            $prefs = $info->addChild('pref');

            foreach ($preferences as $key => $value) {
                $prefs->addChild($key, is_bool($value) ? ($value ? 1 : 0) : $value);
            }
        }

        $info->addChild('site-id', $properties['site-id']);
        $info->addChild('name', $properties['name']);

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

}
