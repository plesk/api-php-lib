<?php

namespace PleskX\Api\Struct\Server\LicenseAdditional;

use PleskX\Api\Struct\Server\LicensePropertyInfo;


/**
 * Struttura dati per le informazioni su una licenza
 */
class keyAdditionalInfo extends \PleskX\Api\Struct {
	
    /**
	 * Numero completo della licenza
	 * @var string 
	 */
    public $key_number;
	
    /**
	 * Nome completo della licenza
	 * @var string
	 */
    public $full_name;
	
    /**
	 * Indica se la licenza è installata e funzionante. False se scaduta
	 * @var bool
	 */
    public $active;
	
    /**
	 * Data di scadenza licenza in formato YYYYMMDD
	 * @var string
	 */
    public $date_expiry;
	
    /**
	 * Collezione di proprietà della licenza
	 * @var \PleskX\Api\Struct\Server\LicensePropertyInfo[] 
	 */
    public $properties;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties( $apiResponse, [
            ['number' => 'key_number'],
            ['name' => 'full_name'],
            ['active' => 'active'],
        ]);
		
		$this->date_expiry = '';
		if( isset( $apiResponse->lim_date ) ) {
			$this->date_expiry = $apiResponse->lim_date;
		}
		
		$this->properties = [];
		
		if( isset( $apiResponse->property ) ) {
			foreach( $apiResponse->property as $property ) {
				$this->properties[] = new LicensePropertyInfo( $property );
			}
		}
    }
}