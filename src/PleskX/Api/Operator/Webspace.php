<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends \PleskX\Api\Operator
{

    public function getPermissionDescriptor()
    {
        $response = $this->request('get-permission-descriptor.filter');
        return new Struct\PermissionDescriptor($response);
    }

    public function getLimitDescriptor()
    {
        $response = $this->request('get-limit-descriptor.filter');
        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor()
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');
        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param array $properties
     * @param array|null $hostingProperties
     * @param $planName
     * @return Struct\Info
     */
    public function create(array $properties, array $hostingProperties = null, $planName = null)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('add');

        $infoGeneral = $info->addChild('gen_setup');
        foreach ($properties as $name => $value) {
            $infoGeneral->addChild($name, $value);
        }

        if ($hostingProperties) {
            $infoHosting = $info->addChild('hosting')->addChild('vrt_hst');
            foreach ($hostingProperties as $name => $value) {
                $property = $infoHosting->addChild('property');
                $property->addChild('name', $name);
                $property->addChild('value', $value);
            }

            if (isset($properties['ip_address'])) {
                $infoHosting->addChild("ip_address", $properties['ip_address']);
            }
        }

        if ($planName) {
            $info->addChild('plan-name', $planName);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

	/**
	 * Imposta lo stato di sospensione al webhosting
	 * @param array $filters
	 * @param int $status
	 * @return bool
	 */
	public function setStatus( array $filters, $status ) {
		$packet = $this->_client->getPacket();
		$setterTag = $packet->addChild( $this->_wrapperTag )->addChild( 'set' );
		if ( !empty( $filters ) ) {
			$filterTag = $setterTag->addChild( 'filter' );
			foreach ( $filters as $key => $value ) {
				$filterTag->addChild( $key, $value );
			}
		}
		$valuesTag = $setterTag->addChild( 'values' );
		$valuesTag->addChild( 'gen_setup' )->addChild( 'status', $status );
		$response = $this->_client->request( $packet );

		return 'ok' === (string)$response->status;
	}

	/**
	 * @param string $field
	 * @param integer|string $value
	 * @return bool
	 */
	public function delete( $field, $value ) {
		return $this->_delete( $field, $value );
	}

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $items = $this->_getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);
        return reset($items);
    }
	
	
	/**
	 * @param string $name
	 * @return string
	 */
	public function getIdByName( $name ) {
		$result = $this->_getId( 'name', $name );
		
		if( isset( $result[0] ) ) {
			return $result[0];
		}
		
		return null;
	}
	
	
    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_getItems(Struct\GeneralInfo::class, 'gen_info');
    }

}
