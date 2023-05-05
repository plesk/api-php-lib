<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Certificate;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public ?string $request = null;
    public ?string $privateKey = null;
    public ?string $publicKey = null;
    public ?string $publicKeyCA = null;

    public function __construct($input)
    {
        if ($input instanceof \SimpleXMLElement) {
            $this->initScalarProperties($input, [
                ['csr' => 'request'],
                ['pvt' => 'privateKey'],
            ]);
        } else {
            foreach ($input as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    public function getMapping(): array
    {
        return array_filter([
            'csr' => $this->request,
            'pvt' => $this->privateKey,
            'cert' => $this->publicKey,
            'ca' => $this->publicKeyCA,
        ]);
    }
}
