<?php

namespace PleskX\Api\Struct\Server;


use PleskX\Api\AbstractStruct;

/**
 * Struttura dati per le informazioni su una proprietà di una licenza
 */
class LicensePropertyInfo extends AbstractStruct {
	
    /** @var string Nome della proprietà */
    public $name;
	
    /** @var string Valore della proprietà */
    public $value;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
		$this->initScalarProperties( $apiResponse, [
            'name',
            'value',
        ]);
    }
}