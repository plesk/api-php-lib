<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Aps as Struct;

class Aps extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create(array $domaininfo,array $package,array $database,array $settings)
    {
        $packet = $this->_client->getPacket();

        $infoInstall = $packet->addChild($this->_wrapperTag)->addChild('install');
        
        foreach ($domaininfo as $name => $value) {
            $infoInstall->addChild($name, $value);
        }
        $infoPackage = $infoInstall->addChild('package');
        foreach($package as $name => $value)
        {
            $infoPackage->addChild($name,$value);
        }
        $infoInstall->addChild('ssl', true);

        $infoDatabase = $infoInstall->addChild('database');
        foreach($database as $name => $value)
        {

            $infoDatabase->addChild($name,$value);
        }

        $infoSettings = $infoInstall->addChild('settings');
        foreach($settings as $settings_array => $setting_array)
        {
            $infoSetting = $infoSettings->addChild('setting');
            foreach($setting_array as $name => $value)
            {
                $infoSetting->addChild($name,$value);
            }
        }      

        $response = $this->_client->request($packet);

        $info = new Struct\Info($response);

        return $info;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $items = $this->_getItems(Struct\GeneralInfo::class, 'install', $field, $value);
        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_getItems(Struct\GeneralInfo::class, 'aps');
    }

}
