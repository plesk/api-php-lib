<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api;

/**
 * Internal client for Plesk XML-RPC API (via SDK).
 */
class InternalClient extends Client
{
    public function __construct()
    {
        parent::__construct('localhost', 0, 'sdk');
    }

    /**
     * Setup login to execute requests under certain user.
     *
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }
}
