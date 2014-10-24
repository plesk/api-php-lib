<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class CertificateTest extends TestCase
{

    public function testGenerate()
    {
        $certificate = $this->_client->certificate()->generate([
            'bits' => 2048,
            'country' => 'RU',
            'state' => 'NSO',
            'location' => 'Novosibirsk',
            'company' => 'Parallels',
            'email' => 'info@example.com',
            'name' => 'example.com'
        ]);
        $this->assertGreaterThan(0, strlen($certificate->request));
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $certificate->request);
        $this->assertGreaterThan(0, strlen($certificate->privateKey));
        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $certificate->privateKey);
    }

}
