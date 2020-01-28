<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Server as Struct;
use PleskX\Api\Exception;


class Server extends \PleskX\Api\Operator
{

    /**
     * @return array
     */
    public function getProtos()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild($this->_wrapperTag)->addChild('get_protos');
        $response = $this->_client->request($packet);

        return (array)$response->protos->proto;
    }

    public function getGeneralInfo()
    {
        return new Struct\GeneralInfo($this->_getInfo('gen_info'));
    }

    public function getPreferences()
    {
        return new Struct\Preferences($this->_getInfo('prefs'));
    }

    public function getAdmin()
    {
        return new Struct\Admin($this->_getInfo('admin'));
    }

    /**
     * @return array
     */
    public function getKeyInfo()
    {
        $keyInfo = [];
        $keyInfoXml = $this->_getInfo('key');

        foreach ($keyInfoXml->property as $property) {
            $keyInfo[(string)$property->name] = (string)$property->value;
        }

        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getComponents()
    {
        $components = [];
        $componentsXml = $this->_getInfo('components');

        foreach ($componentsXml->component as $component) {
            $components[(string)$component->name] = (string)$component->version;
        }

        return $components;
    }

    /**
     * @return array
     */
    public function getServiceStates()
    {
        $states = [];
        $statesXml = $this->_getInfo('services_state');

        foreach ($statesXml->srv as $service) {
            $states[(string)$service->id] = [
                'id' => (string)$service->id,
                'title' => (string)$service->title,
                'state' => (string)$service->state,
            ];
        }

        return $states;
    }

    public function getSessionPreferences()
    {
        return new Struct\SessionPreferences($this->_getInfo('session_setup'));
    }

    /**
     * @return array
     */
    public function getShells()
    {
        $shells = [];
        $shellsXml = $this->_getInfo('shells');

        foreach ($shellsXml->shell as $shell) {
            $shells[(string)$shell->name] = (string)$shell->path;
        }

        return $shells;
    }

    /**
     * @return array
     */
    public function getNetworkInterfaces()
    {
        $interfacesXml = $this->_getInfo('interfaces');
        return (array)$interfacesXml->interface;
    }

    public function getStatistics()
    {
        return new Struct\Statistics($this->_getInfo('stat'));
    }

    /**
     * @return array
     */
    public function getSiteIsolationConfig()
    {
        $config = [];
        $configXml = $this->_getInfo('site-isolation-config');

        foreach ($configXml->property as $property) {
            $config[(string)$property->name] = (string)$property->value;
        }

        return $config;
    }

    public function getUpdatesInfo()
    {
        return new Struct\UpdatesInfo($this->_getInfo('updates'));
    }

    /**
     * @param string $login
     * @param string $clientIp
     * @return string
     */
    public function createSession($login, $clientIp)
    {
        $packet = $this->_client->getPacket();
        $sessionNode = $packet->addChild($this->_wrapperTag)->addChild('create_session');
        $sessionNode->addChild('login', $login);
        $dataNode = $sessionNode->addChild('data');
        $dataNode->addChild('user_ip', base64_encode($clientIp));
        $dataNode->addChild('source_server');
        $response = $this->_client->request($packet);

        return (string)$response->id;
    }

    /**
     * @param string $operation
     * @return \SimpleXMLElement
     */
    private function _getInfo($operation)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild($this->_wrapperTag)->addChild('get')->addChild($operation);
        $response = $this->_client->request($packet);

        return $response->$operation;
    }

	
	/**
	 * Restituisce i dati della licenza eventualmente installata sul server
     * @return Struct\LicenseInfo
	 */
	public function getLicenseInfo() {
        $packet = $this->_client->getPacket();
		$packet->addChild( $this->_wrapperTag )->addChild( 'get' )->addChild( 'key' );
		
		$response = $this->_client->request( $packet );
		
		return new Struct\LicenseInfo( $response );
	}

	
	/**
	 * Restituisce i dati della licenze aggiuntive eventualmente installate sul server
     * @return Struct\LicenseAdditionalInfo
	 */
	public function getAdditionalLicensesInfo() {
        $packet = $this->_client->getPacket();
		$packet->addChild( $this->_wrapperTag )->addChild( 'get_additional_key' );
		
		$response = new Struct\LicenseAdditionalInfo( $this->_client->request( $packet ) );
		
		if( !is_null( $response->error_code ) or !empty( $response->error_message ) ) {
			throw new Exception( $response->error_message, $response->error_code );
		}
		
		return $response;
	}

	
	/**
	 * Installa una licenza principale o aggiuntiva
	 * @param string $activationCode
	 * @param bool $isAdditionalLicense
     * @return Struct\LicenseInstall
	 */
	public function installLicense( $activationCode, $isAdditionalLicense = false ) {
        $packet = $this->_client->getPacket();
		$server = $packet->addChild( $this->_wrapperTag );
		$licInstall = $server->addChild( 'lic_install' );
		
		$licInstall->addChild( 'activation-code', $activationCode );
		
		if( $isAdditionalLicense ) {
			$licInstall->addChild( 'additional_key' );
		}
		
		$response = new Struct\LicenseInstall( $this->_client->request( $packet ) );
		
		if( !is_null( $response->error_code ) or !empty( $response->error_message ) ) {
			throw new Exception( $response->error_message, $response->error_code );
		}
		
		return $response;
	}
}
