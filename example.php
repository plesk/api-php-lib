<?php

require_once('PleskApiClient.php');

$host = getenv('REMOTE_HOST');
$login = getenv('REMOTE_LOGIN');
$password = getenv('REMOTE_PASSWORD');

$client = new PleskApiClient($host);
$client->setCredentials($login, $password);

$request = <<<EOF
<packet version="1.6.3.0">
  <server>
    <get_protos/>
  </server>
</packet>
EOF;

$response = $client->request($request);
echo $response;
