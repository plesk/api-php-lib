<?php

namespace PleskX\Api\Struct\Server\LicenseAdditional;

use PleskX\Api\AbstractStruct;
use PleskX\Api\Struct\Server\LicensePropertyInfo;


/**
 * Struttura dati per le informazioni su una licenza
 */
class keyAdditionalInfo extends AbstractStruct {
	
    /** @var string Numero completo della licenza */
    public $key_number;
	
    /** @var string Nome completo della licenza */
    public $full_name;
	
    /** @var bool Indica se la licenza è installata e funzionante. False se scaduta */
    public $active;
	
    /** @var string Data di scadenza licenza in formato YYYYMMDD */
    public $date_expiry;
	
    /** @var \PleskX\Api\Struct\Server\LicensePropertyInfo[] Collezione di proprietà della licenza */
    public $properties;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
        $this->initScalarProperties( $apiResponse, [
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