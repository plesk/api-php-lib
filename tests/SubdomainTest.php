<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class SubdomainTest extends TestCase
{

    /**
     * @param string $name
     * @param string $webspaceName
     * @return \PleskX\Api\Struct\Subdomain\Info
     */
    private function _createSubdomain($name, $webspaceName)
    {
        return $this->_client->subdomain()->create([
            'parent' => $webspaceName,
            'name' => $name,
        ]);
    }

    public function testCreate()
    {
        $webspaceName = 'example.tld';
        $webspace = $this->_createWebspace($webspaceName);
        $subdomain = $this->_createSubdomain('sub', $webspaceName);

        $this->assertInternalType('integer', $subdomain->id);
        $this->assertGreaterThan(0, $subdomain->id);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspaceName = 'example.tld';
        $webspace = $this->_createWebspace($webspaceName);
        $subdomain = $this->_createSubdomain('sub', $webspaceName);

        $result = $this->_client->subdomain()->delete('id', $subdomain->id);
        $this->assertTrue($result);
        $this->_client->webspace()->delete('id', $webspace->id);
    }
}
