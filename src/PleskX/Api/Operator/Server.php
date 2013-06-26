<?php

class PleskX_Api_Operator_Server
{

    private $_client;

    public function __construct($client)
    {
        $this->_client = $client;
    }

    public function getProtos()
    {
        $request = <<<EOF
        <packet version="1.6.3.0">
            <server>
                <get_protos/>
            </server>
        </packet>
EOF;

        $response = $this->_client->request($request);
        return (array)$response->server->get_protos->result->protos->proto;
    }

}
