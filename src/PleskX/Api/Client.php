<?php

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

        // TODO: add error handling
        $xml = simplexml_load_string($result);

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
     * Server operator
     *
     * @return Operator\Server
     */
    public function server()
    {
        static $serverOperator;

        if (!$serverOperator) {
            $serverOperator = new Operator\Server($this);
        }

        return $serverOperator;
    }

    /**
     * Certificate operator
     *
     * @return Operator\Certificate
     */
    public function certificate()
    {
        static $certificateOperator;

        if (!$certificateOperator) {
            $certificateOperator = new Operator\Certificate($this);
        }

        return $certificateOperator;
    }

    /**
     * Customer operator
     *
     * @return Operator\Customer
     */
    public function customer()
    {
        static $customerOperator;

        if (!$customerOperator) {
            $customerOperator = new Operator\Customer($this);
        }

        return $customerOperator;
    }

}
