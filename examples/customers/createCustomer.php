<?php
include("../../vendor/autoload.php");

use PleskX\API\Client as PleskApiClient;

/**
 * Plesk Authentication credentials.
 * Change the host, login, password below with your own.
 */

 $host     = "";
 $login    = "";
 $password = "";

 $client = new PleskApiClient($host);
 $client->setCredentials($login, $password);

 try {
     $customer = $client->customer()->create(array(
    'cname'   => 'Example',
    'pname'   => 'Example customer',
    'login'   => 'example',
    'passwd'  => 'Jhtr66fBB',
    'status'  => '0',
    'phone'   => '416 907 9944',
    'fax'     => '928 752 3905',
    'email'   => 'demo@example.com',
    'address' => '105 Brisbane Road, Unit 2',
    'city'    => 'Toronto',
    'state'   => '',
    'pcode'   => '',
    'country' => 'CA'
  ));

     // Show result
     echo "<pre>";
     print_r($customer);
     echo "</pre>";
 } catch (\PleskX\API\Client\Exception $e) {
     echo 'Please verify your credentials. Make sure to change the $host, $login, and $password variables in this file.';
 }
