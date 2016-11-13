<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api;

/**
 * Internal client for Plesk API-RPC (via SDK)
 */
class InternalClient extends Client
{
    public function __construct()
    {
        parent::__construct('localhost', 0, 'sdk');
    }
}