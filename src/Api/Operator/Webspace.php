<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;
use PleskX\Api\XmlResponse;

class Webspace extends Operator
{
    public function getPermissionDescriptor(): Struct\PermissionDescriptor
    {
        $response = $this->request('get-permission-descriptor.filter');

        return new Struct\PermissionDescriptor($response);
    }
	
	
	/**
	 * Restituisce i dettagli dei parametri che indicano i limiti di un server
	 * Ad esempio per il parametro che indica il numero di cpu verranno restituite
	 * informazioni come il codice del parametro, il data type, i permessi di scrittura, ...
	 * @return \PleskX\Api\Struct\Webspace\LimitDescriptor
	 */
    public function getLimitDescriptor(): Struct\LimitDescriptor
    {
        $response = $this->request('get-limit-descriptor.filter');

        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor(): Struct\PhysicalHostingDescriptor
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');

        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\PhpSettings
     */
    public function getPhpSettings(string $field, $value): Struct\PhpSettings
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $getTag->addChild('filter')->addChild($field, (string) $value);
        $getTag->addChild('dataset')->addChild('php-settings');

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        return new Struct\PhpSettings($response);
    }

    /**
	 * ORIGINAL PLESK IMPLEMENTATION
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Limits
     */
    public function getLimits(string $field, $value): Struct\Limits
    {
        $items = $this->getItems(Struct\Limits::class, 'limits', $field, $value);

        return reset($items);
    }

    /**
     * @param array $properties
     * @param array|null $hostingProperties
     * @param string $planName
     *
     * @return Struct\Info
     */
    public function create(array $properties, array $hostingProperties = null, string $planName = ''): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add');

        $infoGeneral = $info->addChild('gen_setup');
        foreach ($properties as $name => $value) {
            if (is_array($value)) {
                continue;
            } else {
                $infoGeneral->addChild($name, (string) $value);
            }
        }

        if ($hostingProperties) {
            $infoHosting = $info->addChild('hosting')->addChild('vrt_hst');
            foreach ($hostingProperties as $name => $value) {
                $property = $infoHosting->addChild('property');
                $property->name = $name;
                $property->value = $value;
            }

            if (isset($properties['ip_address'])) {
                foreach ((array) $properties['ip_address'] as $ipAddress) {
                    $infoHosting->addChild('ip_address', $ipAddress);
                }
            }
        }

        if ('' !== $planName) {
            $info->addChild('plan-name', $planName);
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response, $properties['name'] ?? '');
    }
    
    
	/**
	 * @param $webspaceId
	 *
	 * @return string|null
	 */
	public function getStatus( $webspaceId )
	{
		$packet = $this->client->getPacket();
		$getterTag = $packet->addChild( $this->wrapperTag )->addChild( 'get' );
		
		$filterTag = $getterTag->addChild( 'filter' );
		$filterTag->addChild( 'id', $webspaceId );
		
		$getterTag->addChild( 'dataset' )->addChild( 'gen_info' );
		
		$response = $this->client->request( $packet );
		
		if( !isset( $response->data->gen_info->status ) ) {
			return null;
		}
		
		return trim( $response->data->gen_info->status );
	}
	
	
	/**
	 * Imposta lo stato di sospensione al webhosting
	 * @param array $filters
	 * @param int $status
	 * @return bool
	 */
	public function setStatus( array $filters, $status )
	{
		$packet = $this->client->getPacket();
		$setterTag = $packet->addChild( $this->wrapperTag )->addChild( 'set' );
		if ( !empty( $filters ) ) {
			$filterTag = $setterTag->addChild( 'filter' );
			foreach ( $filters as $key => $value ) {
				$filterTag->addChild( $key, $value );
			}
		}
		$valuesTag = $setterTag->addChild( 'values' );
		$valuesTag->addChild( 'gen_setup' )->addChild( 'status', $status );
		$response = $this->client->request( $packet );

		return 'ok' === (string)$response->status;
	}
	

	/**
	 * @param array $filters
	 * @param string $newPassword
	 * @return bool
	 */
	public function updateFtpPassword( array $filters, $newPassword )
	{
		$packet = $this->client->getPacket();
		$setterTag = $packet->addChild( $this->wrapperTag )->addChild( 'set' );
		if ( !empty( $filters ) ) {
			$filterTag = $setterTag->addChild( 'filter' );
			foreach ( $filters as $key => $value ) {
				$filterTag->addChild( $key, $value );
			}
		}
		
		$valuesTag = $setterTag->addChild( 'values' );
		$infoHosting = $valuesTag->addChild('hosting')->addChild('vrt_hst');
		$property = $infoHosting->addChild('property');
		
		$property->addChild('name', 'ftp_password');
		$property->addChild('value', $newPassword);
		
		$response = $this->client->request( $packet );

		return 'ok' === (string)$response->status;
	}
	
    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete(string $field, $value): bool
    {
        return $this->deleteBy($field, $value);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get(string $field, $value): Struct\GeneralInfo
    {
        $items = $this->getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items);
    }
	
	
    /**
	 * Restituisce le impostazioni di tipo hosting per una subscription
     * @param int $guid L'identificativo universale della subscription
     * @return Struct\HostingSetting
     */
    public function getHostingSettings( $guid )
    {
        $items = $this->getItems(Struct\HostingSetting::class, 'hosting', 'guid', $guid );
        return reset($items);
    }
	
	
    /**
	 * Restituisce l'identificativo univoco del servizio a listino associato ad una subscription
     * @param int $guid L'identificativo universale della subscription
     * @return string
     */
    public function getPlanGuid( $guid )
    {
		$packet = $this->client->getPacket();
		$getterTag = $packet->addChild( $this->wrapperTag )->addChild( 'get' );
		
		$filterTag = $getterTag->addChild( 'filter' );
        $filterTag->addChild( 'guid', $guid );
		
		$getterTag->addChild( 'dataset' )->addChild( 'subscriptions' );
		
		$response = $this->client->request( $packet );
		
		$guidPropertyName = 'plan-guid';
		
		if( !isset( $response->data->subscriptions->subscription->plan->$guidPropertyName ) ) {
			return null;
		}
		
		return trim( $response->data->subscriptions->subscription->plan->$guidPropertyName );
    }
	
	
	/**
	 * Restituisce l'ID della subscription (diverso dal GUID)
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
	 * Esegue il cambio piano di un hosting
	 * @param int $webspaceId Id della Subscription
	 * @param string $planGuid
	 * @return XmlResponse
	 */
	public function switchSubscription( $webspaceId, $planGuid ) {
		$packet = $this->client->getPacket();
		
		$switchSubscriptionTag = $packet->addChild( $this->wrapperTag )->addChild( 'switch-subscription' );
		
		$switchSubscriptionTag->addChild( 'filter' )->addChild( 'id', $webspaceId );
		$switchSubscriptionTag->addChild( 'plan-guid', $planGuid );
		
		$response = $this->client->request( $packet );
		
		return $response;
	}
	
	
    /**
	 * Restituisce tutte le subscriptions (servizi attivati) presenti sul server.
	 * Tali subscription potrebbero anche essere in uno stato non attivo
     * @return Struct\GeneralInfo[]
     */
    public function getAll(): array
    {
        return $this->getItems(Struct\GeneralInfo::class, 'gen_info');
    }
	
	
    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\DiskUsage
     */
    public function getDiskUsage(string $field, $value): Struct\DiskUsage
    {
        $items = $this->getItems(Struct\DiskUsage::class, 'disk_usage', $field, $value);

        return reset($items);
    }
	
	
	/**
	 * @param array $filters
	 * @param string|null $certificateName
	 * @return bool
	 */
	public function setCurrentCertificate( $filters, $certificateName ) {
		$packet = $this->client->getPacket();
		$setterTag = $packet->addChild( $this->wrapperTag )->addChild( 'set' );
		if ( !empty( $filters ) ) {
			$filterTag = $setterTag->addChild( 'filter' );
			foreach ( $filters as $key => $value ) {
				$filterTag->addChild( $key, $value );
			}
		}
		
		$valuesTag = $setterTag->addChild( 'values' );
		$infoHosting = $valuesTag->addChild('hosting')->addChild('vrt_hst');
		$property = $infoHosting->addChild('property');
		
		$property->addChild('name', 'certificate_name');
		$property->addChild('value', $certificateName);
		
		$response = $this->client->request( $packet );
		
		return 'ok' === (string)$response->status;
	}
	
	
	/**
	 * @param array $filters
	 * @return string|null
	 */
	public function getCurrentCertificate( $filters ) {
		$packet = $this->client->getPacket();
		$getterTag = $packet->addChild( $this->wrapperTag )->addChild( 'get' );
		
		if ( !empty( $filters ) ) {
			$filterTag = $getterTag->addChild( 'filter' );
			
			foreach( $filters as $key => $value ) {
				$filterTag->addChild( $key, $value );
			}
		}
		
		$getterTag->addChild( 'dataset' )->addChild( 'hosting' );
		
		$response = $this->client->request( $packet );
		$responseProperties = $response->data->hosting->vrt_hst->property;
		
		foreach( $responseProperties as $property ) {
			if( $property->name == 'certificate_name' ) {
				return trim( $property->value );
			}
		}
		
		return null;
	}

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function enable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 0]);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function disable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 1]);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param array $properties
     *
     * @return bool
     */
    public function setProperties(string $field, $value, array $properties): bool
    {
        $packet = $this->client->getPacket();
        $setTag = $packet->addChild($this->wrapperTag)->addChild('set');
        $setTag->addChild('filter')->addChild($field, (string) $value);
        $genInfoTag = $setTag->addChild('values')->addChild('gen_setup');
        foreach ($properties as $property => $propertyValue) {
            $genInfoTag->addChild($property, (string) $propertyValue);
        }

        $response = $this->client->request($packet);

        return 'ok' === (string) $response->status;
    }
}
