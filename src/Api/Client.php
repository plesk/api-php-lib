<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api;

use SimpleXMLElement;

/**
 * Client for Plesk XML-RPC API.
 */
class Client
{
    const RESPONSE_SHORT = 1;
    const RESPONSE_FULL = 2;

    protected string $_host;
    protected int $_port;
    protected string $_protocol;
    protected string $_login = '';
    protected string $_password = '';
    protected string $_proxy = '';
    protected string $_secretKey = '';
    protected string $_version = '';

    protected array $_operatorsCache = [];

    /**
     * @var callable|null
     */
    protected $_verifyResponseCallback;

    /**
     * Create client.
     *
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    public function __construct(string $host, int $port = 8443, string $protocol = 'https')
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_protocol = $protocol;
    }

    /**
     * Setup credentials for authentication.
     *
     * @param string $login
     * @param string $password
     */
    public function setCredentials(string $login, string $password): void
    {
        $this->_login = $login;
        $this->_password = $password;
    }

    /**
     * Define secret key for alternative authentication.
     *
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey): void
    {
        $this->_secretKey = $secretKey;
    }

    /**
     * Set proxy server for requests.
     *
     * @param string $proxy
     */
    public function setProxy(string $proxy): void
    {
        $this->_proxy = $proxy;
    }

    /**
     * Set default version for requests.
     *
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->_version = $version;
    }

    /**
     * Set custom function to verify response of API call according your own needs. Default verifying will be used if it is not specified.
     *
     * @param callable|null $function
     */
    public function setVerifyResponse(callable $function = null): void
    {
        $this->_verifyResponseCallback = $function;
    }

    /**
     * Retrieve host used for communication.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->_host;
    }

    /**
     * Retrieve port used for communication.
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->_port;
    }

    /**
     * Retrieve name of the protocol (http or https) used for communication.
     *
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->_protocol;
    }

    /**
     * Retrieve XML template for packet.
     *
     * @param string|null $version
     *
     * @return SimpleXMLElement
     */
    public function getPacket($version = null): SimpleXMLElement
    {
        $protocolVersion = !is_null($version) ? $version : $this->_version;
        $content = "<?xml version='1.0' encoding='UTF-8' ?>";
        $content .= '<packet'.('' === $protocolVersion ? '' : " version='$protocolVersion'").'/>';

        return new SimpleXMLElement($content);
    }

    /**
     * Perform API request.
     *
     * @param string|array|SimpleXMLElement $request
     * @param int $mode
     *
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
            } elseif (preg_match('/^[a-z]/', $request)) {
                $request = $this->_expandRequestShortSyntax($request, $xml);
            }
        }

        if ('sdk' == $this->_protocol) {
            $version = ('' == $this->_version) ? null : $this->_version;
            $requestXml = new SimpleXMLElement((string) $request);
            /** @psalm-suppress UndefinedClass */
            $xml = \pm_ApiRpc::getService($version)->call($requestXml->children()[0]->asXml(), $this->_login);
        } else {
            $xml = $this->_performHttpRequest((string) $request);
        }

        $this->_verifyResponseCallback
            ? call_user_func($this->_verifyResponseCallback, $xml)
            : $this->_verifyResponse($xml);

        return (self::RESPONSE_FULL == $mode) ? $xml : $xml->xpath('//result')[0];
    }

    /**
     * Perform HTTP request to end-point.
     *
     * @param string $request
     *
     * @throws Client\Exception
     *
     * @return XmlResponse
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

        if ('' !== $this->_proxy) {
            curl_setopt($curl, CURLOPT_PROXY, $this->_proxy);
        }

        $result = curl_exec($curl);

        if (false === $result) {
            throw new Client\Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        return new XmlResponse((string) $result);
    }

    /**
     * Perform multiple API requests using single HTTP request.
     *
     * @param array $requests
     * @param int $mode
     *
     * @throws Client\Exception
     *
     * @return array
     */
    public function multiRequest(array $requests, $mode = self::RESPONSE_SHORT): array
    {
        $requestXml = $this->getPacket();

        foreach ($requests as $request) {
            if ($request instanceof SimpleXMLElement) {
                throw new Client\Exception('SimpleXML type of request is not supported for multi requests.');
            } else {
                if (is_array($request)) {
                    $request = $this->_arrayToXml($request, $requestXml)->asXML();
                    if (!$request) {
                        throw new Client\Exception('Failed to create an XML string for request');
                    }
                } elseif (preg_match('/^[a-z]/', $request)) {
                    $this->_expandRequestShortSyntax($request, $requestXml);
                }
            }
        }

        if ('sdk' == $this->_protocol) {
            throw new Client\Exception('Multi requests are not supported via SDK.');
        } else {
            $xmlString = $requestXml->asXML();
            if (!$xmlString) {
                throw new Client\Exception('Failed to create an XML string for request');
            }
            $responseXml = $this->_performHttpRequest($xmlString);
        }

        $responses = [];
        foreach ($responseXml->children() as $childNode) {
            $xml = $this->getPacket();
            $dom = dom_import_simplexml($xml)->ownerDocument;
            if (!$dom) {
                continue;
            }

            $childDomNode = dom_import_simplexml($childNode);
            $childDomNode = $dom->importNode($childDomNode, true);
            $dom->documentElement->appendChild($childDomNode);

            $response = simplexml_load_string($dom->saveXML());
            $responses[] = (self::RESPONSE_FULL == $mode) ? $response : $response->xpath('//result')[0];
        }

        return $responses;
    }

    /**
     * Retrieve list of headers needed for request.
     *
     * @return array
     */
    protected function _getHeaders()
    {
        $headers = [
            'Content-Type: text/xml',
            'HTTP_PRETTY_PRINT: TRUE',
        ];

        if ($this->_secretKey) {
            $headers[] = "KEY: $this->_secretKey";
        } else {
            $headers[] = "HTTP_AUTH_LOGIN: $this->_login";
            $headers[] = "HTTP_AUTH_PASSWD: $this->_password";
        }

        return $headers;
    }

    /**
     * Verify that response does not contain errors.
     *
     * @param XmlResponse $xml
     *
     * @throws Exception
     */
    protected function _verifyResponse($xml): void
    {
        if ($xml->system && $xml->system->status && 'error' == (string) $xml->system->status) {
            throw new Exception((string) $xml->system->errtext, (int) $xml->system->errcode);
        }

        if ($xml->xpath('//status[text()="error"]') && $xml->xpath('//errcode') && $xml->xpath('//errtext')) {
            $errorCode = (int) $xml->xpath('//errcode')[0];
            $errorMessage = (string) $xml->xpath('//errtext')[0];

            throw new Exception($errorMessage, $errorCode);
        }
    }

    /**
     * Expand short syntax (some.method.call) into full XML representation.
     *
     * @param string $request
     * @param SimpleXMLElement $xml
     *
     * @return false|string
     */
    protected function _expandRequestShortSyntax($request, SimpleXMLElement $xml)
    {
        $parts = explode('.', $request);
        $node = $xml;
        $lastParts = end($parts);

        foreach ($parts as $part) {
            @list($name, $value) = explode('=', $part);
            if ($part !== $lastParts) {
                $node = $node->addChild($name);
            } else {
                $node->{$name} = (string) $value;
            }
        }

        return $xml->asXML();
    }

    /**
     * Convert array to XML representation.
     *
     * @param array $array
     * @param SimpleXMLElement $xml
     * @param string $parentEl
     *
     * @return SimpleXMLElement
     */
    protected function _arrayToXml(array $array, SimpleXMLElement $xml, $parentEl = null)
    {
        foreach ($array as $key => $value) {
            $el = is_int($key) && $parentEl ? $parentEl : $key;
            if (is_array($value)) {
                $this->_arrayToXml($value, $this->_isAssocArray($value) ? $xml->addChild($el) : $xml, $el);
            } else {
                $xml->{$el} = (string) $value;
            }
        }

        return $xml;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    protected function _isAssocArray(array $array)
    {
        return $array && array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function _getOperator(string $name)
    {
        if (!isset($this->_operatorsCache[$name])) {
            $className = '\\PleskX\\Api\\Operator\\'.$name;
            /** @psalm-suppress InvalidStringClass */
            $this->_operatorsCache[$name] = new $className($this);
        }

        return $this->_operatorsCache[$name];
    }

    public function server(): Operator\Server
    {
        return $this->_getOperator('Server');
    }

    public function customer(): Operator\Customer
    {
        return $this->_getOperator('Customer');
    }

    public function webspace(): Operator\Webspace
    {
        return $this->_getOperator('Webspace');
    }

    public function subdomain(): Operator\Subdomain
    {
        return $this->_getOperator('Subdomain');
    }

    public function dns(): Operator\Dns
    {
        return $this->_getOperator('Dns');
    }

    public function dnsTemplate(): Operator\DnsTemplate
    {
        return $this->_getOperator('DnsTemplate');
    }

    public function databaseServer(): Operator\DatabaseServer
    {
        return $this->_getOperator('DatabaseServer');
    }

    public function mail(): Operator\Mail
    {
        return $this->_getOperator('Mail');
    }

    public function certificate(): Operator\Certificate
    {
        return $this->_getOperator('Certificate');
    }

    public function siteAlias(): Operator\SiteAlias
    {
        return $this->_getOperator('SiteAlias');
    }

    public function ip(): Operator\Ip
    {
        return $this->_getOperator('Ip');
    }

    public function eventLog(): Operator\EventLog
    {
        return $this->_getOperator('EventLog');
    }

    public function secretKey(): Operator\SecretKey
    {
        return $this->_getOperator('SecretKey');
    }

    public function ui(): Operator\Ui
    {
        return $this->_getOperator('Ui');
    }

    public function servicePlan(): Operator\ServicePlan
    {
        return $this->_getOperator('ServicePlan');
    }

    public function virtualDirectory(): Operator\VirtualDirectory
    {
        return $this->_getOperator('VirtualDirectory');
    }

    public function database(): Operator\Database
    {
        return $this->_getOperator('Database');
    }

    public function session(): Operator\Session
    {
        return $this->_getOperator('Session');
    }

    public function locale(): Operator\Locale
    {
        return $this->_getOperator('Locale');
    }

    public function logRotation(): Operator\LogRotation
    {
        return $this->_getOperator('LogRotation');
    }

    public function protectedDirectory(): Operator\ProtectedDirectory
    {
        return $this->_getOperator('ProtectedDirectory');
    }

    public function reseller(): Operator\Reseller
    {
        return $this->_getOperator('Reseller');
    }

    public function resellerPlan(): Operator\ResellerPlan
    {
        return $this->_getOperator('ResellerPlan');
    }

    public function aps(): Operator\Aps
    {
        return $this->_getOperator('Aps');
    }

    public function servicePlanAddon(): Operator\ServicePlanAddon
    {
        return $this->_getOperator('ServicePlanAddon');
    }

    public function site(): Operator\Site
    {
        return $this->_getOperator('Site');
    }

    public function phpHandler(): Operator\PhpHandler
    {
        return $this->_getOperator('PhpHandler');
    }
}
