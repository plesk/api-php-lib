<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PHPUnit\Framework\TestCase;
use PleskX\Api\Client;
use PleskXTest\Utility\PasswordProvider;

abstract class AbstractTestCase extends TestCase
{
    protected static Client $client;

    private static array $webspaces = [];
    private static array $servicePlans = [];
    private static array $servicePlanAddons = [];

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

        static::$client = new Client($host, $port, $scheme);
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

        foreach (self::$servicePlanAddons as $servicePlanAddon) {
            try {
                static::$client->servicePlanAddon()->delete('id', $servicePlanAddon->id);
                // phpcs:ignore
            } catch (\Exception $e) {
            }
        }
    }

    protected static function getIpAddress(): string
    {
        $ips = static::$client->ip()->get();
        $ipInfo = reset($ips);

        return $ipInfo->ipAddress;
    }

    protected static function createWebspace(): \PleskX\Api\Struct\Webspace\Info
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

    protected static function createServicePlan(): \PleskX\Api\Struct\ServicePlan\Info
    {
        $id = uniqid();
        $servicePlan = static::$client->servicePlan()->create(['name' => "test{$id}plan"]);

        self::$servicePlans[] = $servicePlan;

        return $servicePlan;
    }

    protected static function createServicePlanAddon(): \PleskX\Api\Struct\ServicePlanAddon\Info
    {
        $id = uniqid();
        $servicePlanAddon = static::$client->servicePlanAddon()->create(['name' => "test{$id}planaddon"]);

        self::$servicePlanAddons[] = $servicePlanAddon;

        return $servicePlanAddon;
    }
}
