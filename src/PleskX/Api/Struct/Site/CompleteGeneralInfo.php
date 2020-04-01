<?php

namespace PleskX\Api\Struct\Site;


class CompleteGeneralInfo extends \PleskX\Api\Struct
{
	/**
	 * Nome della subscription. E' il nome a dominio
	 * @var string 
	 */
    public $Name;

	/**
	 * Data creazione subscription. Formato YYYY-MM-DD
	 * @var string 
	 */
    public $CreationDate;

	/**
	 * Nome della subscription in formato ASCII. E' il nome a dominio in formato ASCII
	 * @var string
	 */
    public $AsciiName;

	/**
	 * Codice dello stato della subscription
	 * Allowed values:
	 *		0 (active)
	 *		4 (under backup/restore)
	 *		16 (disabled by Plesk Administrator)
	 *		32 (disabled by Plesk reseller)
	 *		64 (disabled by Plesk customer)
	 *		256 (expired)
	 * @var string 
	 */
    public $Status;

	/**
	 * Dimensione della subscription (bytes)
	 * @var string unsignedLong 
	 */
    public $RealSize;

	/**
	 * Subscription GUID
	 * @var string 
	 */
    public $Guid;
	
	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse )
    {
		if( isset( $apiResponse->guid ) ) {
			$this->Guid = $apiResponse->guid;
		}
		
		if( isset( $apiResponse->name ) ) {
			$this->Name = $apiResponse->name;
		}
		
		if( isset( $apiResponse->cr_date ) ) {
			$this->CreationDate = $apiResponse->cr_date;
		}
		
		$asciiPropertyName = 'ascii-name';
		if( isset( $apiResponse->$asciiPropertyName ) ) {
			$this->AsciiName = $apiResponse->$asciiPropertyName;
		}
		
		if( isset( $apiResponse->status ) ) {
			$this->Status = (string) $apiResponse->status;
		}
		
		if( isset( $apiResponse->real_size ) ) {
			$this->RealSize = (string) $apiResponse->real_size;
		}
    }
}