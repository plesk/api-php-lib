<?php

class TestCase_Abstract extends PHPUnit_Framework_TestCase
{

    /** @var PleskX_Api_Client */
    protected $_client;

    public function setUp()
    {
        $host = getenv('REMOTE_HOST');
        $login = getenv('REMOTE_LOGIN');
        $password = getenv('REMOTE_PASSWORD');

        $this->_client = new PleskX_Api_Client($host);
        $this->_client->setCredentials($login, $password);
    }

}
