<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function generate($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }


	/**
	 * @param $properties
	 * @return Struct\InstallInfo
	 */
	public function install($properties)
	{
		$packet = $this->_client->getPacket();
		$install = $packet->addChild($this->_wrapperTag)->addChild('install');

		foreach ($properties as $name => $value) {
			if ($name == 'content') {
				$content = $install->addChild('content');

				foreach ($value as $contentKey => $contentValue) {
					$content->addChild($contentKey, $contentValue);
				}

				continue;
			}

			$install->addChild($name, $value);
		}

		$response = $this->_client->request($packet);
		return new Struct\InstallInfo($response);
	}
}
