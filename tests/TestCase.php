<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\PasswordProvider;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \PleskX\Api\Client */
    protected static $_client;

    private static $webspaces = [];
    private static $servicePlans = [];

    public static function setUpBeforeClass(): void
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

        static::$_client = new \PleskX\Api\Client($host, $port, $scheme);
        static::$_client->setCredentials($login, $password);

        if ($proxy = getenv('REMOTE_PROXY')) {
            static::$_client->setProxy($proxy);
        }
    }

    public static function tearDownAfterClass(): void
    {
        foreach (self::$webspaces as $webspace) {
            try {
                static::$_client->webspace()->delete('id', $webspace->id);
            } catch (\Exception $e) {
            }
        }

        foreach (self::$servicePlans as $servicePlan) {
            try {
                static::$_client->servicePlan()->delete('id', $servicePlan->id);
            } catch (\Exception $e) {
            }
        }
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
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    protected static function _createWebspace()
    {
        $id = uniqid();
        $webspace = static::$_client->webspace()->create(
            [
                'name' => "test{$id}.test",
                'ip_address' => static::_getIpAddress(),
            ],
            [
                'ftp_login' => "u{$id}",
                'ftp_password' => PasswordProvider::STRONG_PASSWORD,
            ]
        );
        self::$webspaces[] = $webspace;

        return $webspace;
    }

    protected static function _createServicePlan()
    {
        $id = uniqid();
        $servicePlan = static::$_client->servicePlan()->create(['name' => "test{$id}plan"]);

        self::$servicePlans[] = $servicePlan;

        return $servicePlan;
    }
}
