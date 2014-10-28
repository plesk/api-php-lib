<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class LocaleTest extends TestCase
{

    public function testGet()
    {
        $locales = $this->_client->locale()->get();
        $this->assertGreaterThan(0, count($locales));

        $locale = $locales['en-US'];
        $this->assertEquals('en-US', $locale->id);
    }

    public function testGetById()
    {
        $locale = $this->_client->locale()->get('en-US');
        $this->assertEquals('en-US', $locale->id);
    }

}
