<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api;
use SimpleXMLElement;

/**
 * Client for Plesk XML-RPC API
 */
class Client
{
    const RESPONSE_SHORT = 1;
    const RESPONSE_FULL = 2;

    protected $_host;
    protected $_port;
    protected $_protocol;
    protected $_login;
    protected $_password;
    protected $_secretKey;
    protected $_version = '';

    protected static $_isExecutionsLogEnabled = false;
    protected static $_executionLog = [];

    protected $_operatorsCache = [];

    /**
     * @var callable
     */
    protected $_verifyResponseCallback;

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
     * Set custom function to verify response of API call according your own needs. Default verifying will be used if it is not specified
     *
     * @param callable|null $function
     */
    public function setVerifyResponse(callable $function = null)
    {
        $this->_verifyResponseCallback = $function;
    }

    /**
     * Retrieve host used for communication
     *
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * Retrieve port used for communication
     *
     * @return int
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * Retrieve name of the protocol (http or https) used for communication
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->_protocol;
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
     * @param string|array|SimpleXMLElement $request
     * @param int $mode
     * @return XmlResponse
     */
    public function request($request, $mode = self::RESPONSE_SHORT)
    {
        if ($request instanceof SimpleXMLElement) {
            $request = $request->asXml();
        } else {
            $xml = $this->getPacket();

            if (is_array($request)) {
                $request = $this->_arrayToXml($request, $xml)->asXML();
            } else if (preg_match('/^[a-z]/', $request)) {
                $request = $this->_expandRequestShortSyntax($request, $xml);
            }
        }

        if ('sdk' == $this->_protocol) {
            $version = ('' == $this->_version) ? null : $this->_version;
            $requestXml = new SimpleXMLElement((string)$request);
            $xml = \pm_ApiRpc::getService($version)->call($requestXml->children()[0]->asXml(), $this->_login);
        } else {
            $xml = $this->_performHttpRequest($request);
        }

        $this->_verifyResponseCallback
            ? call_user_func($this->_verifyResponseCallback, $xml)
            : $this->_verifyResponse($xml);

        return (self::RESPONSE_FULL == $mode) ? $xml : $xml->xpath('//result')[0];
    }

    /**
     * Perform HTTP request to end-point
     *
     * @param string $request
     * @return XmlResponse
     * @throws Client\Exception
     */
    private function _performHttpRequest($request)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->_protocol://$this->_host:$this->_port/enterprise/control/agent.php");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($curl);

        if (false === $result) {
            throw new Client\Exception(curl_error($curl), curl_errno($curl));
        }

        if (self::$_isExecutionsLogEnabled) {
            self::$_executionLog[] = [
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
                'request' => $request,
                'response' => $result,
            ];
        }

        curl_close($curl);

        $xml = new XmlResponse($result);
        return $xml;
    }

    /**
     * Perform multiple API requests using single HTTP request
     *
     * @param $requests
     * @param int $mode
     * @return array
     * @throws Client\Exception
     */
    public function multiRequest($requests, $mode = self::RESPONSE_SHORT)
    {

        $requestXml = $this->getPacket();

        foreach ($requests as $request) {
            if ($request instanceof SimpleXMLElement) {
                throw new Client\Exception('SimpleXML type of request is not supported for multi requests.');
            } else {
                if (is_array($request)) {
                    $request = $this->_arrayToXml($request, $requestXml)->asXML();
                } else if (preg_match('/^[a-z]/', $request)) {
                    $this->_expandRequestShortSyntax($request, $requestXml);
                }
            }
            $responses[] = $this->request($request);
        }

        if ('sdk' == $this->_protocol) {
            throw new Client\Exception('Multi requests are not supported via SDK.');
        } else {
            $responseXml = $this->_performHttpRequest($requestXml->asXML());
        }

        $responses = [];
        foreach ($responseXml->children() as $childNode) {
            $xml = $this->getPacket();
            $dom = dom_import_simplexml($xml)->ownerDocument;

            $childDomNode = dom_import_simplexml($childNode);
            $childDomNode = $dom->importNode($childDomNode, true);
            $dom->documentElement->appendChild($childDomNode);

            $response = simplexml_load_string($dom->saveXML());
            $responses[] = (self::RESPONSE_FULL == $mode) ? $response : $response->xpath('//result')[0];
        }

        return $responses;
    }

    /**
     * Retrieve list of headers needed for request
     *
     * @return array
     */
    protected function _getHeaders()
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
     * @param XmlResponse $xml
     * @throws Exception
     */
    protected function _verifyResponse($xml)
    {
        if ($xml->system && $xml->system->status && 'error' == (string)$xml->system->status) {
            throw new Exception((string)$xml->system->errtext, (int)$xml->system->errcode);
        }

        if ($xml->xpath('//status[text()="error"]') && $xml->xpath('//errcode') && $xml->xpath('//errtext')) {
            $errorCode = (int)$xml->xpath('//errcode')[0];
            $errorMessage = (string)$xml->xpath('//errtext')[0];
            throw new Exception($errorMessage, $errorCode);
        }
    }

    /**
     * Expand short syntax (some.method.call) into full XML representation
     *
     * @param string $request
     * @param SimpleXMLElement $xml
     * @return string
     */
    protected function _expandRequestShortSyntax($request, SimpleXMLElement $xml)
    {
        $parts = explode('.', $request);
        $node = $xml;

        foreach ($parts as $part) {
        	if ( strpos( $part, '=') !== false ) {
				list($name, $value) = explode('=', $part);
				$node = $node->addChild($name, $value);
			} else {
				$node = $node->addChild($part);
			}
        }

        return $xml->asXML();
    }

    /**
     * Convert array to XML representation
     *
     * @param array $array
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    protected function _arrayToXml(array $array, SimpleXMLElement $xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->_arrayToXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }

    /**
     * @param string $name
     * @return \PleskX\Api\Operator
     */
    protected function _getOperator($name)
    {
        if (!isset($this->_operatorsCache[$name])) {
            $className = '\\PleskX\\Api\\Operator\\' . $name;
            $this->_operatorsCache[$name] = new $className($this);
        }

        return $this->_operatorsCache[$name];
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
