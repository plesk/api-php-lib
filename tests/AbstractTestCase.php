<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\PasswordProvider;

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \PleskX\Api\Client */
    protected static $client;

    private static $webspaces = [];
    private static $servicePlans = [];

    public static function setUpBeforeClass(): void
    {
        $login = getenv('REMOTE_LOGIN') ?: 'admin';
        $password = getenv('REMOTE_PASSWORD') ?: 'changeme1Q**';
        $host = getenv('REMOTE_HOST') ?: 'localhost';
        $port = 8443;
        $scheme = 'https';

        $url = getenv('REMOTE_URL');
        if ($url) {
            $parsedUrl = parse_url($url);
            list($host, $port, $scheme) = [$parsedUrl['host'], $parsedUrl['port'], $parsedUrl['scheme']];
        }

        static::$client = new \PleskX\Api\Client($host, $port, $scheme);
        static::$client->setCredentials($login, $password);

        $proxy = getenv('REMOTE_PROXY');
        if ($proxy) {
            static::$client->setProxy($proxy);
        }
    }

    public static function tearDownAfterClass(): void
    {
        foreach (self::$webspaces as $webspace) {
            try {
                static::$client->webspace()->delete('id', $webspace->id);
                // phpcs:ignore
            } catch (\Exception $e) {
            }
        }

        foreach (self::$servicePlans as $servicePlan) {
            try {
                static::$client->servicePlan()->delete('id', $servicePlan->id);
                // phpcs:ignore
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @return string
     */
    protected static function getIpAddress()
    {
        $ips = static::$client->ip()->get();
        $ipInfo = reset($ips);

        return $ipInfo->ipAddress;
    }

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    protected static function createWebspace()
    {
        $id = uniqid();
        $webspace = static::$client->webspace()->create(
            [
                'name' => "test{$id}.test",
                'ip_address' => static::getIpAddress(),
            ],
            [
                'ftp_login' => "u{$id}",
                'ftp_password' => PasswordProvider::STRONG_PASSWORD,
            ]
        );
        self::$webspaces[] = $webspace;

        return $webspace;
    }

    protected static function createServicePlan()
    {
        $id = uniqid();
        $servicePlan = static::$client->servicePlan()->create(['name' => "test{$id}plan"]);

        self::$servicePlans[] = $servicePlan;

        return $servicePlan;
    }
}
