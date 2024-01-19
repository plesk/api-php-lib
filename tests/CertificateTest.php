<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskX\Api\Struct\Certificate as Struct;

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
    private array $distinguishedNames = [
        "countryName" => "CH",
        "stateOrProvinceName" => "Schaffhausen",
        "localityName" => "Schaffhausen",
        "organizationName" => "Plesk",
        "emailAddress" => "info@plesk.com"
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

    public function testUpdate()
    {
        $payLoad = [
            'name' => 'test',
            'admin' => true,
        ];
        $certificate = static::$client->certificate()->generate($this->certificateProperties);
        static::$client->certificate()->install($payLoad, $certificate);

        $certificate = $this->generateCertificateOpenSsl($this->distinguishedNames);
        $result = static::$client->certificate()->update($payLoad, $certificate);
        $this->assertTrue($result);

        static::$client->certificate()->delete('test', ['admin' => true]);
    }

    public function testDelete()
    {
        $certificate = static::$client->certificate()->generate($this->certificateProperties);

        static::$client->certificate()->install([
            'name' => 'test',
            'admin' => true,
        ], $certificate);

        $result = static::$client->certificate()->delete('test', ['admin' => true]);
        $this->assertTrue($result);
    }

    private function generateCertificateOpenSsl(array $dn): Struct\Info
    {
        $privkey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        $csr = openssl_csr_new($dn, $privkey, ['digest_alg' => 'sha256']);
        $x509 = openssl_csr_sign($csr, null, $privkey, $days = 365, ['digest_alg' => 'sha256']);

        openssl_csr_export($csr, $csrout);
        openssl_x509_export($x509, $certout);
        openssl_pkey_export($privkey, $pkeyout);

        return new Struct\Info([
            'publicKey' => $certout,
            'request' => $csrout,
            'privateKey' => $pkeyout
        ]);
    }
}
