<?php

namespace PleskX\Api\Struct\FtpUser;

class Info extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $home;

    /** @var integer */
    public $webspaceId;
	
	
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'home',
            'webspace_id',
        ]);
    }
}