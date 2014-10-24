#!/usr/bin/env php
<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

require_once('PleskApiClient.php');

$host = getenv('REMOTE_HOST');
$login = getenv('REMOTE_LOGIN') ?: 'admin';
$password = getenv('REMOTE_PASSWORD');

$client = new PleskApiClient($host);
$client->setCredentials($login, $password);

$request = <<<EOF
<packet>
  <server>
    <get_protos/>
  </server>
</packet>
EOF;

$response = $client->request($request);
echo $response;
