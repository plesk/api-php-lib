<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskXTest;

class CertificateTest extends AbstractTestCase
{
    private array $certificateProperties = [
        'bits' => 2048,
        'country' => 'CH',
        'state' => 'Schaffhausen',
        'location' => 'Schaffhausen',
        'company' => 'Plesk',
        'email' => 'info@plesk.com',
        'name' => 'plesk.com',
    ];

    public function testGenerate()
    {
        $certificate = static::$client->certificate()->generate($this->certificateProperties);
        $this->assertGreaterThan(0, strlen($certificate->request));
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $certificate->request);
        $this->assertGreaterThan(0, strlen($certificate->privateKey));
        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $certificate->privateKey);
    }

    public function testInstall()
    {
        $certificate = static::$client->certificate()->generate($this->certificateProperties);

        $result = static::$client->certificate()->install([
            'name' => 'test',
            'admin' => true,
        ], $certificate->request, $certificate->privateKey);
        $this->assertTrue($result);

        static::$client->certificate()->delete('test', ['admin' => true]);
    }

    public function testDelete()
    {
        $certificate = static::$client->certificate()->generate($this->certificateProperties);

        static::$client->certificate()->install([
            'name' => 'test',
            'admin' => true,
        ], $certificate->request, $certificate->privateKey);

        $result = static::$client->certificate()->delete('test', ['admin' => true]);
        $this->assertTrue($result);
    }
}
