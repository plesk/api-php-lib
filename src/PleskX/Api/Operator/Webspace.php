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
	
	
	/**
	 * Restituisce i dettagli dei parametri che indicano i limiti di un server
	 * Ad esempio per il parametro che indica il numero di cpu verranno restituite
	 * informazioni come il codice del parametro, il data type, i permessi di scrittura, ...
	 * @return \PleskX\Api\Struct\Webspace\LimitDescriptor
	 */
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
	 * @param array $filters
	 * @param string $newPassword
	 * @return bool
	 */
	public function updateFtpPassword( array $filters, $newPassword ) {
		$packet = $this->_client->getPacket();
		$setterTag = $packet->addChild( $this->_wrapperTag )->addChild( 'set' );
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
	 * Restituisce i limiti massimi per una subscription
     * @param int $guid L'identificativo universale della subscription
     * @return Struct\Limit
     */
    public function getLimits( $guid )
    {
        $items = $this->_getItems(Struct\Limit::class, 'limits', 'guid', $guid );
        return reset($items);
    }
	
	
    /**
	 * Restituisce le impostazioni di tipo hosting per una subscription
     * @param int $guid L'identificativo universale della subscription
     * @return Struct\HostingSetting
     */
    public function getHostingSettings( $guid )
    {
        $items = $this->_getItems(Struct\HostingSetting::class, 'hosting', 'guid', $guid );
        return reset($items);
    }
	
	
    /**
	 * Restituisce l'identificativo univoco del servizio a listino associato ad una subscription
     * @param int $guid L'identificativo universale della subscription
     * @return string
     */
    public function getPlanGuid( $guid )
    {
		$packet = $this->_client->getPacket();
		$getterTag = $packet->addChild( $this->_wrapperTag )->addChild( 'get' );
		
		$filterTag = $getterTag->addChild( 'filter' );
        $filterTag->addChild( 'guid', $guid );
		
		$getterTag->addChild( 'dataset' )->addChild( 'subscriptions' );
		
		$response = $this->_client->request( $packet );
		
		$guidPropertyName = 'plan-guid';
		
		if( !isset( $response->data->subscriptions->subscription->plan->$guidPropertyName ) ) {
			return null;
		}
		
		return trim( reset( $response->data->subscriptions->subscription->plan->$guidPropertyName ) );
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
		$packet = $this->_client->getPacket();
		
		$switchSubscriptionTag = $packet->addChild( $this->_wrapperTag )->addChild( 'switch-subscription' );
		
		$switchSubscriptionTag->addChild( 'filter' )->addChild( 'id', $webspaceId );
		$switchSubscriptionTag->addChild( 'plan-guid', $planGuid );
		
		$response = $this->_client->request( $packet );
		
		return $response;
	}
	
	
    /**
	 * Restituisce tutte le subscriptions (servizi attivati) presenti sul server.
	 * Tali subscription potrebbero anche essere in uno stato non attivo
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_getItems(Struct\GeneralInfo::class, 'gen_info');
    }
	
	
    /**
	 * Restituisce tutte le subscriptions (servizi attivati) presenti sul server.
	 * Tali subscription potrebbero anche essere in uno stato non attivo
     * @return Struct\CompleteGeneralInfo[]
     */
    public function getCompleteList()
    {
        return $this->_getItems( Struct\CompleteGeneralInfo::class, 'gen_info' );
    }
}