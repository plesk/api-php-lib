<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends \PleskX\Api\Operator
{

    public function getPermissionDescriptor()
    {
        $response = $this->request('get-permission-descriptor.filter');
        return new Struct\PermissionDescriptor($response);
    }

    public function getLimitDescriptor()
    {
        $response = $this->request('get-limit-descriptor.filter');
        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor()
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');
        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param array $properties // see https://docs.plesk.com/en-US/12.5/api-rpc/reference/managing-subscriptions-webspaces/subscription-settings/general-subscription-information/node-gen_setup.33858/
     * @param array $more_prop // see https://docs.plesk.com/en-US/12.5/api-rpc/reference/managing-subscriptions-webspaces/creating-a-subscription-webspace.33892/
     * @param string $ftp_login // ftp login
     * @param string $ftp_pass // ftp password
     * @param string $ip // IP address of webspace
     * @return Struct\Info
     */
    public function create($properties, $more_prop = null, $ftp_login = "", $ftp_pass = "", $ip = "")
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild('webspace')->addChild('add');

        $info_setup = $info->addChild('gen_setup');

        foreach ($properties as $name => $value) {
            $info_setup->addChild($name, $value);
        }

        $info_props = $info->addChild('hosting')->addChild('vrt_hst');

        if($ftp_login != "" && $ftp_pass != "")
        {
            $prop_ftp_user = $info_props->addChild('property');
            $prop_ftp_user->addChild("name", 'ftp_login');
            $prop_ftp_user->addChild("value", $ftp_login);


            $prop_ftp_pass = $info_props->addChild('property');
            $prop_ftp_pass->addChild("name", 'ftp_password');
            $prop_ftp_pass->addChild("value", $ftp_pass);
        }
		
        if($ip != "")
            $info_props->addChild("ip_address", $ip);

        if($more_prop)
            foreach ($more_prop as $name => $value)
                $info->addChild($name, $value);

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('webspace')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->_client->request($packet);
        return new Struct\GeneralInfo($response->data->gen_info);
    }

}
