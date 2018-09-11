<?php

namespace PleskX\Api\Struct\Webuser;

class Info extends \PleskX\Api\Struct
{

    /**
	 * @var int
	 */
    public $id;

    /**
	 * @var string
	 */
    public $login;
	
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'login',
        ]);
    }
}