<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Certificate;

use PleskX\Api\AbstractStruct;

class InstallInfo extends AbstractStruct
{
	/** @var string */
	public $status;

	public function __construct($apiResponse)
	{
		$this->initScalarProperties($apiResponse, [
			'status',
		]);
	}
}