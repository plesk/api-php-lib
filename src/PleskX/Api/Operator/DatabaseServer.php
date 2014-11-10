<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Operator;

class DatabaseServer extends \PleskX\Api\Operator
{

    protected $_wrapperTag = 'db_server';

    /**
     * @return array
     */
    public function getSupportedTypes()
    {
        $response = $this->request('get-supported-types');
        return (array)$response->type;
    }

}
