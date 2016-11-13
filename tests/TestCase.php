<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

abstract class TestCase extends PHPUnit_Framework_TestCase
{

    /** @var PleskX\Api\Client */
    protected $_client;

    protected function setUp()
    {
        $login = getenv('REMOTE_LOGIN');
        $password = getenv('REMOTE_PASSWORD');
        $host = getenv('REMOTE_HOST');
        $port = 8443;
        $scheme = 'https';

        if ($url = getenv('REMOTE_URL')) {
            $parsedUrl = parse_url($url);
            list($host, $port, $scheme) = [$parsedUrl['host'], $parsedUrl['port'], $parsedUrl['scheme']];
        }

        $this->_client = new PleskX\Api\Client($host, $port, $scheme);
        $this->_client->setCredentials($login, $password);
    }

    /**
     * @return string
     */
    protected function _getIpAddress()
    {
        $ips = $this->_client->ip()->get();
        $ipInfo = reset($ips);
        return $ipInfo->ipAddress;
    }

}
