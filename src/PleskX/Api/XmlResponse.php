<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api;

/**
 * XML wrapper for responses
 */
class XmlResponse extends \SimpleXMLElement
{

    /**
     * Retrieve value by node name
     *
     * @param string $node
     * @return string
     */
    public function getValue($node)
    {
        return (string)$this->xpath('//' . $node)[0];
    }

}