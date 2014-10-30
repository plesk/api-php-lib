<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api;

class Operator
{

    /** @var string|null */
    protected $_wrapperTag = null;

    /** @var \PleskX\Api\Client */
    protected $_client;

    public function __construct($client)
    {
        $this->_client = $client;
    }

    /**
     * Perform plain API request
     *
     * @param string $request
     * @return XmlResponse
     */
    public function request($request)
    {
        $wrapperTag = $this->_wrapperTag;

        if (is_null($wrapperTag)) {
            $wrapperTag = end(explode('\\', get_class($this)));
            $wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $wrapperTag));
        }

        if (preg_match('/^[a-z]/', $request)) {
            $request = "$wrapperTag.$request";
        } else {
            $request = "<$wrapperTag>$request</$wrapperTag>";
        }

        return $this->_client->request($request);
    }

}
