<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Session as Struct;

class Session extends \PleskX\Api\Operator
{
    /**
     * @param string $username
     * @param string $userIp
     *
     * @return string
     */
    public function create($username, $userIp)
    {
        $packet = $this->client->getPacket();
        $creator = $packet->addChild('server')->addChild('create_session');

        $creator->addChild('login', $username);
        $loginData = $creator->addChild('data');

        $loginData->addChild('user_ip', base64_encode($userIp));
        $loginData->addChild('source_server', '');

        $response = $this->client->request($packet);

        return (string) $response->id;
    }

    /**
     * @return Struct\Info[]
     */
    public function get()
    {
        $sessions = [];
        $response = $this->request('get');

        foreach ($response->session as $sessionInfo) {
            $sessions[(string) $sessionInfo->id] = new Struct\Info($sessionInfo);
        }

        return $sessions;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function terminate($sessionId)
    {
        $response = $this->request("terminate.session-id=$sessionId");

        return 'ok' === (string) $response->status;
    }
}
