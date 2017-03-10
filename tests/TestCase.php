<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

abstract class TestCase extends PHPUnit_Framework_TestCase
{

    /** @var PleskX\Api\Client */
    protected static $_client;

    public static function setUpBeforeClass()
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

        static::$_client = new PleskX\Api\Client($host, $port, $scheme);
        static::$_client->setCredentials($login, $password);
    }

    /**
     * @return string
     */
    protected static function _getIpAddress()
    {
        $ips = static::$_client->ip()->get();
        $ipInfo = reset($ips);
        return $ipInfo->ipAddress;
    }

    /**
     * @param string $name
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    protected static function _createWebspace($name)
    {
        return static::$_client->webspace()->create([
            'name' => $name,
            'ip_address' => static::_getIpAddress(),
        ], [
            'ftp_login' => 'test-login',
            'ftp_password' => 'TEST-password',
        ]);
    }

}
