<?php

namespace PleskX\Api\Struct\FtpUser;

class Info extends \PleskX\Api\Struct
{

    /**
	 * Stato della richiesta
	 * @var string
	 */
    public $status;

    /**
	 * Nome utenza ftp
	 * @var string
	 */
    public $filterId;

    /**
	 * Identificatore univoco dell'account ftp
	 * @var int
	 */
    public $id;

	
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties($apiResponse, [
            'status',
            'filter-id',
            'id',
        ]);
    }
}