<?php

namespace PleskX\Api\Struct\FtpUser;

class Info extends \PleskX\Api\Struct
{

    /**
	 * @var int
	 */
    public $id;

    /**
	 * @var int
	 */
    public $name;

    /**
	 * @var int
	 */
    public $home;

    /**
	 * @var int
	 */
    public $quota;

    /**
	 * @var int
	 */
    public $webspaceId;
	
	
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'home',
            'quota',
            'webspace_id',
        ]);
    }
}