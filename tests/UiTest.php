<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

class UiTest extends TestCase
{

    private $_customButtonProperties = [
        'place' => 'admin',
        'url' => 'http://example.com',
        'text' => 'Example site',
    ];

    public function testGetNavigation()
    {
        $navigation = $this->_client->ui()->getNavigation();
        $this->assertInternalType('array', $navigation);
        $this->assertGreaterThan(0, count($navigation));
        $this->assertArrayHasKey('general', $navigation);
        $this->assertArrayHasKey('hosting', $navigation);

        $hostingSection = $navigation['hosting'];
        $this->assertArrayHasKey('name', $hostingSection);
        $this->assertArrayHasKey('nodes', $hostingSection);
        $this->assertGreaterThan(0, count($hostingSection['nodes']));
    }

    public function testCreateCustomButton()
    {
        $buttonId = $this->_client->ui()->createCustomButton('admin', $this->_customButtonProperties);
        $this->assertGreaterThan(0, $buttonId);

        $this->_client->ui()->deleteCustomButton($buttonId);
    }

    public function testGetCustomButton()
    {
        $buttonId = $this->_client->ui()->createCustomButton('admin', $this->_customButtonProperties);
        $customButtonInfo = $this->_client->ui()->getCustomButton($buttonId);
        $this->assertEquals('http://example.com', $customButtonInfo->url);
        $this->assertEquals('Example site', $customButtonInfo->text);

        $this->_client->ui()->deleteCustomButton($buttonId);
    }

    public function testDeleteCustomButton()
    {
        $buttonId = $this->_client->ui()->createCustomButton('admin', $this->_customButtonProperties);
        $result = $this->_client->ui()->deleteCustomButton($buttonId);
        $this->assertTrue($result);
    }

}
