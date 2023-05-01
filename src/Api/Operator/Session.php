<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Session as Struct;

class Session extends \PleskX\Api\Operator
{
    public function create(string $username, string $userIp): string
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
    public function get(): array
    {
        $sessions = [];
        $response = $this->request('get');

        foreach ($response->session as $sessionInfo) {
            $sessions[(string) $sessionInfo->id] = new Struct\Info($sessionInfo);
        }

        return $sessions;
    }

    public function terminate(string $sessionId): bool
    {
        $response = $this->request("terminate.session-id=$sessionId");

        return 'ok' === (string) $response->status;
    }
}
