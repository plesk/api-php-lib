<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api;
use SimpleXMLElement;

/**
 * Client for Plesk API-RPC
 */
class Client
{
    private $_host;
    private $_port;
    private $_protocol;
    private $_login;
    private $_password;
    private $_secretKey;
    private $_version = '';

    private static $_isExecutionsLogEnabled = false;
    private static $_executionLog = [];

    private static $_operatorsCache = [];

    /**
     * Create client
     *
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    public function __construct($host, $port = 8443, $protocol = 'https')
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_protocol = $protocol;
    }

    /**
     * Setup credentials for authentication
     *
     * @param string $login
     * @param string $password
     */
    public function setCredentials($login, $password)
    {
        $this->_login = $login;
        $this->_password = $password;
    }

    /**
     * Define secret key for alternative authentication
     *
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->_secretKey = $secretKey;
    }

    /**
     * Set default version for requests
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Retrieve XML template for packet
     *
     * @param string|null $version
     * @return SimpleXMLElement
     */
    public function getPacket($version = null)
    {
        $protocolVersion = !is_null($version) ? $version : $this->_version;
        $content = "<?xml version='1.0' encoding='UTF-8' ?>";
        $content .= "<packet" . ("" === $protocolVersion ? "" : " version='$protocolVersion'") . "/>";
        return new SimpleXMLElement($content);
    }

    /**
     * Perform API request
     *
     * @param string|SimpleXMLElement $request
     * @return string
     */
    public function request($request)
    {
        if ($request instanceof SimpleXMLElement) {
            $request = $request->asXml();
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->_protocol://$this->_host:$this->_port/enterprise/control/agent.php");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($curl);

        if (self::$_isExecutionsLogEnabled) {
            self::$_executionLog[] = [
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
                'request' => $request,
                'response' => $result,
            ];
        }

        curl_close($curl);

        $xml = simplexml_load_string($result);
        $this->_verifyResponse($xml);

        return $xml;
    }

    /**
     * Retrieve list of headers needed for request
     *
     * @return array
     */
    private function _getHeaders()
    {
        $headers = array(
            "Content-Type: text/xml",
            "HTTP_PRETTY_PRINT: TRUE",
        );

        if ($this->_secretKey) {
            $headers[] = "KEY: $this->_secretKey";
        } else {
            $headers[] = "HTTP_AUTH_LOGIN: $this->_login";
            $headers[] = "HTTP_AUTH_PASSWD: $this->_password";
        }

        return $headers;
    }

    /**
     * Enable or disable execution log
     *
     * @param bool $enable
     */
    public static function enableExecutionLog($enable = true)
    {
        self::$_isExecutionsLogEnabled = $enable;
    }

    /**
     * Retrieve execution log
     *
     * @return array
     */
    public static function getExecutionLog()
    {
        return self::$_executionLog;
    }

    /**
     * Verify that response does not contain errors
     *
     * @param SimpleXMLElement $xml
     * @throws \Exception
     */
    private function _verifyResponse($xml)
    {
        if ($xml->system && $xml->system->status && 'error' == (string)$xml->system->status) {
            throw new Exception((string)$xml->system->errtext, (int)$xml->system->errcode);
        }
    }

    /**
     * @param string $name
     * @return \PleskX\Api\Operator
     */
    private function _getOperator($name)
    {
        if (!isset(self::$_operatorsCache[$name])) {
            $className = '\\PleskX\\Api\\Operator\\' . $name;
            self::$_operatorsCache[$name] = new $className($this);
        }

        return self::$_operatorsCache[$name];
    }

    /**
     * @return Operator\Server
     */
    public function server()
    {
        return $this->_getOperator('Server');
    }

    /**
     * @return Operator\Customer
     */
    public function customer()
    {
        return $this->_getOperator('Customer');
    }

    /**
     * @return Operator\Webspace
     */
    public function webspace()
    {
        return $this->_getOperator('Webspace');
    }

    /**
     * @return Operator\Subdomain
     */
    public function subdomain()
    {
        return $this->_getOperator('Subdomain');
    }

    /**
     * @return Operator\Dns
     */
    public function dns()
    {
        return $this->_getOperator('Dns');
    }

    /**
     * @return Operator\DatabaseServer
     */
    public function databaseServer()
    {
        return $this->_getOperator('DatabaseServer');
    }

    /**
     * @return Operator\Mail
     */
    public function mail()
    {
        return $this->_getOperator('Mail');
    }

    /**
     * @return Operator\Migration
     */
    public function migration()
    {
        return $this->_getOperator('Migration');
    }

    /**
     * @return Operator\Certificate
     */
    public function certificate()
    {
        return $this->_getOperator('Certificate');
    }

    /**
     * @return Operator\SiteAlias
     */
    public function siteAlias()
    {
        return $this->_getOperator('SiteAlias');
    }

    /**
     * @return Operator\Ip
     */
    public function ip()
    {
        return $this->_getOperator('Ip');
    }

    /**
     * @return Operator\EventLog
     */
    public function eventLog()
    {
        return $this->_getOperator('EventLog');
    }

    /**
     * @return Operator\SpamFilter
     */
    public function spamFilter()
    {
        return $this->_getOperator('SpamFilter');
    }

    /**
     * @return Operator\SecretKey
     */
    public function secretKey()
    {
        return $this->_getOperator('SecretKey');
    }

    /**
     * @return Operator\Ui
     */
    public function ui()
    {
        return $this->_getOperator('Ui');
    }

    /**
     * @return Operator\ServicePlan
     */
    public function servicePlan()
    {
        return $this->_getOperator('ServicePlan');
    }

    /**
     * @return Operator\WebUser
     */
    public function webUser()
    {
        return $this->_getOperator('WebUser');
    }

    /**
     * @return Operator\MailList
     */
    public function mailList()
    {
        return $this->_getOperator('MailList');
    }

    /**
     * @return Operator\VirtualDirectory
     */
    public function virtualDirectory()
    {
        return $this->_getOperator('VirtualDirectory');
    }

    /**
     * @return Operator\Database
     */
    public function database()
    {
        return $this->_getOperator('Database');
    }

    /**
     * @return Operator\FtpUser
     */
    public function ftpUser()
    {
        return $this->_getOperator('FtpUser');
    }

    /**
     * @return Operator\Session
     */
    public function session()
    {
        return $this->_getOperator('Session');
    }

    /**
     * @return Operator\Updater
     */
    public function updater()
    {
        return $this->_getOperator('Updater');
    }

    /**
     * @return Operator\Locale
     */
    public function locale()
    {
        return $this->_getOperator('Locale');
    }

    /**
     * @return Operator\LogRotation
     */
    public function logRotation()
    {
        return $this->_getOperator('LogRotation');
    }

    /**
     * @return Operator\BackupManager
     */
    public function backupManager()
    {
        return $this->_getOperator('BackupManager');
    }

    /**
     * @return Operator\Sso
     */
    public function sso()
    {
        return $this->_getOperator('Sso');
    }

    /**
     * @return Operator\ProtectedDirectory
     */
    public function protectedDirectory()
    {
        return $this->_getOperator('ProtectedDirectory');
    }

    /**
     * @return Operator\Reseller
     */
    public function reseller()
    {
        return $this->_getOperator('Reseller');
    }

    /**
     * @return Operator\ResellerPlan
     */
    public function resellerPlan()
    {
        return $this->_getOperator('ResellerPlan');
    }

    /**
     * @return Operator\Aps
     */
    public function aps()
    {
        return $this->_getOperator('Aps');
    }

    /**
     * @return Operator\ServicePlanAddon
     */
    public function servicePlanAddon()
    {
        return $this->_getOperator('ServicePlanAddon');
    }

    /**
     * @return Operator\Site
     */
    public function site()
    {
        return $this->_getOperator('Site');
    }

    /**
     * @return Operator\User
     */
    public function user()
    {
        return $this->_getOperator('User');
    }

    /**
     * @return Operator\Role
     */
    public function role()
    {
        return $this->_getOperator('Role');
    }

    /**
     * @return Operator\BusinessLogicUpgrade
     */
    public function businessLogicUpgrade()
    {
        return $this->_getOperator('BusinessLogicUpgrade');
    }

    /**
     * @return Operator\Webmail
     */
    public function webmail()
    {
        return $this->_getOperator('Webmail');
    }

    /**
     * @return Operator\PlanItem
     */
    public function planItem()
    {
        return $this->_getOperator('PlanItem');
    }

    /**
     * @return Operator\Sitebuilder
     */
    public function sitebuilder()
    {
        return $this->_getOperator('Sitebuilder');
    }

    /**
     * @return Operator\ServiceNode
     */
    public function serviceNode()
    {
        return $this->_getOperator('ServiceNode');
    }

    /**
     * @return Operator\IpBan
     */
    public function ipBan()
    {
        return $this->_getOperator('IpBan');
    }

    /**
     * @return Operator\WpInstance
     */
    public function wpInstance()
    {
        return $this->_getOperator('WpInstance');
    }

}
