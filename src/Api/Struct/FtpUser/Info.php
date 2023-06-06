<?php

namespace PleskX\Api\Struct\FtpUser;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
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
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'home',
            'webspace_id',
        ]);
    }
}