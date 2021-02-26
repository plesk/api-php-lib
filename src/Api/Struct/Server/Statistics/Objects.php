<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Objects extends \PleskX\Api\Struct
{
    /** @var int */
    public $clients;

    /** @var int */
    public $domains;

    /** @var int */
    public $databases;

    /** @var int */
    public $activeDomains;

    /** @var int */
    public $mailBoxes;

    /** @var int */
    public $mailRedirects;

    /** @var int */
    public $mailGroups;

    /** @var int */
    public $mailResponders;

    /** @var int */
    public $databaseUsers;

    /** @var int */
    public $problemClients;

    /** @var int */
    public $problemDomains;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'clients',
            'domains',
            'databases',
            ['active_domains' => 'activeDomains'],
            ['mail_boxes' => 'mailBoxes'],
            ['mail_redirects' => 'mailRedirects'],
            ['mail_groups' => 'mailGroups'],
            ['mail_responders' => 'mailResponders'],
            ['database_users' => 'databaseUsers'],
            ['problem_clients' => 'problemClients'],
            ['problem_domains' => 'problemDomains'],
        ]);
    }
}
