<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api;

use DOMDocument;
use SimpleXMLElement;

/**
 * Client for Plesk XML-RPC API.
 */
class Client
{
    public const RESPONSE_SHORT = 1;
    public const RESPONSE_FULL = 2;

    private string $host;
    private int $port;
    private string $protocol;
    protected string $login = '';
    private string $password = '';
    private string $proxy = '';
    private string $secretKey = '';
    private string $version = '';

    protected array $operatorsCache = [];

    /**
     * @var callable|null
     */
    protected $verifyResponseCallback;

    /**
     * Create client.
     *
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    public function __construct(string $host, int $port = 8443, string $protocol = 'https')
    {
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
    }

    /**
     * Setup credentials for authentication.
     *
     * @param string $login
     * @param string $password
     */
    public function setCredentials(string $login, string $password): void
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Define secret key for alternative authentication.
     *
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Set proxy server for requests.
     *
     * @param string $proxy
     */
    public function setProxy(string $proxy): void
    {
        $this->proxy = $proxy;
    }

    /**
     * Set default version for requests.
     *
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * Set custom function to verify response of API call according your own needs.
     * Default verifying will be used if it is not specified.
     *
     * @param callable|null $function
     */
    public function setVerifyResponse(callable $function = null): void
    {
        $this->verifyResponseCallback = $function;
    }

    /**
     * Retrieve host used for communication.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieve port used for communication.
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Retrieve name of the protocol (http or https) used for communication.
     *
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
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
        $protocolVersion = !is_null($version) ? $version : $this->version;
        $content = "<?xml version='1.0' encoding='UTF-8' ?>";
        $content .= '<packet' . ('' === $protocolVersion ? '' : " version='$protocolVersion'") . '/>';

        return new SimpleXMLElement($content);
    }

    /**
     * Perform API request.
     *
     * @param string|array|SimpleXMLElement $request
     * @param int $mode
     *
     * @return XmlResponse
     * @throws \Exception
     */
    public function request($request, int $mode = self::RESPONSE_SHORT): XmlResponse
    {
        if ($request instanceof SimpleXMLElement) {
            $request = $request->asXml();
        } else {
            $xml = $this->getPacket();

            if (is_array($request)) {
                $request = $this->arrayToXml($request, $xml)->asXML();
            } elseif (preg_match('/^[a-z]/', $request)) {
                $request = $this->expandRequestShortSyntax($request, $xml);
            }
        }

        if ('sdk' == $this->protocol) {
            $xml = $this->performSdkCall((string) $request);
        } else {
            $xml = $this->performHttpRequest((string) $request);
        }

        $this->verifyResponseCallback
            ? call_user_func($this->verifyResponseCallback, $xml)
            : $this->verifyResponse($xml);

        $result = (self::RESPONSE_FULL === $mode)
            ? $xml
            : ($xml->xpath('//result') ?: [null])[0];

        return new XmlResponse($result ? (string) $result->asXML() : '');
    }

    private function performSdkCall(string $request): XmlResponse
    {
        $version = ('' == $this->version) ? null : $this->version;

        $requestXml = new SimpleXMLElement($request);
        $innerNodes = $requestXml->children();
        $innerXml = $innerNodes && count($innerNodes) > 0 && $innerNodes[0] ? $innerNodes[0]->asXml() : '';

        /** @psalm-suppress UndefinedClass */
        $result = \pm_ApiRpc::getService($version)->call($innerXml, $this->login);

        return new XmlResponse($result ? (string) $result->asXML() : '');
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
    private function performHttpRequest($request)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->protocol://$this->host:$this->port/enterprise/control/agent.php");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        if ('' !== $this->proxy) {
            curl_setopt($curl, CURLOPT_PROXY, $this->proxy);
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
    public function multiRequest(array $requests, int $mode = self::RESPONSE_SHORT): array
    {
        $requestXml = $this->getPacket();

        foreach ($requests as $request) {
            if ($request instanceof SimpleXMLElement) {
                throw new Client\Exception('SimpleXML type of request is not supported for multi requests.');
            } else {
                if (is_array($request)) {
                    $request = $this->arrayToXml($request, $requestXml)->asXML();
                    if (!$request) {
                        throw new Client\Exception('Failed to create an XML string for request');
                    }
                } elseif (preg_match('/^[a-z]/', $request)) {
                    $this->expandRequestShortSyntax($request, $requestXml);
                }
            }
        }

        if ('sdk' == $this->protocol) {
            throw new Client\Exception('Multi requests are not supported via SDK.');
        } else {
            $xmlString = $requestXml->asXML();
            if (!$xmlString) {
                throw new Client\Exception('Failed to create an XML string for request');
            }
            $responseXml = $this->performHttpRequest($xmlString);
        }

        return $this->splitResponseToArray($responseXml, $mode);
    }

    private function splitResponseToArray(XmlResponse $responseXml, $mode = self::RESPONSE_SHORT): array
    {
        $responses = [];

        $nodes = $responseXml->children();
        if (!$nodes) {
            return [];
        }

        foreach ($nodes as $childNode) {
            $dom = $this->getDomDocument($this->getPacket());
            if (!$dom) {
                continue;
            }

            $childDomNode = dom_import_simplexml($childNode);
            if (!is_null($childDomNode)) {
                $childDomNode = $dom->importNode($childDomNode, true);
                $dom->documentElement->appendChild($childDomNode);
            }

            $response = simplexml_load_string($dom->saveXML());
            if (!$response) {
                return [];
            }

            $responses[] = (self::RESPONSE_FULL == $mode)
                ? $response
                : ($response->xpath('//result') ?: [null])[0];
        }

        return $responses;
    }

    private function getDomDocument(SimpleXMLElement $xml): ?DOMDocument
    {
        $dom = dom_import_simplexml($xml);
        if (is_null($dom)) {
            return null;
        }

        return $dom->ownerDocument;
    }

    /**
     * Retrieve list of headers needed for request.
     *
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Content-Type: text/xml',
            'HTTP_PRETTY_PRINT: TRUE',
        ];

        if ($this->secretKey) {
            $headers[] = "KEY: $this->secretKey";
        } else {
            $headers[] = "HTTP_AUTH_LOGIN: $this->login";
            $headers[] = "HTTP_AUTH_PASSWD: $this->password";
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
    private function verifyResponse($xml): void
    {
        if ($xml->system && $xml->system->status && 'error' == (string) $xml->system->status) {
            throw new Exception((string) $xml->system->errtext, (int) $xml->system->errcode);
        }

        if ($xml->xpath('//status[text()="error"]') && $xml->xpath('//errcode') && $xml->xpath('//errtext')) {
            $errorCode = (int) ($xml->xpath('//errcode') ?: [null])[0];
            $errorMessage = (string) ($xml->xpath('//errtext') ?: [null])[0];

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
    private function expandRequestShortSyntax($request, SimpleXMLElement $xml)
    {
        $parts = explode('.', $request);
        $node = $xml;
        $lastParts = end($parts);

        foreach ($parts as $part) {
            // phpcs:ignore
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
    private function arrayToXml(array $array, SimpleXMLElement $xml, $parentEl = null)
    {
        foreach ($array as $key => $value) {
            $el = is_int($key) && $parentEl ? $parentEl : $key;
            if (is_array($value)) {
                $this->arrayToXml($value, $this->isAssocArray($value) ? $xml->addChild($el) : $xml, $el);
            } elseif (!isset($xml->{$el})) {
                $xml->{$el} = (string) $value;
            } else {
                $xml->{$el}[] = (string) $value;
            }
        }

        return $xml;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function isAssocArray(array $array)
    {
        return $array && array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    private function getOperator(string $name)
    {
        if (!isset($this->operatorsCache[$name])) {
            $className = '\\PleskX\\Api\\Operator\\' . $name;
            /** @psalm-suppress InvalidStringClass */
            $this->operatorsCache[$name] = new $className($this);
        }

        return $this->operatorsCache[$name];
    }

    public function server(): Operator\Server
    {
        return $this->getOperator('Server');
    }

    public function customer(): Operator\Customer
    {
        return $this->getOperator('Customer');
    }

    public function webspace(): Operator\Webspace
    {
        return $this->getOperator('Webspace');
    }

    public function subdomain(): Operator\Subdomain
    {
        return $this->getOperator('Subdomain');
    }

    public function dns(): Operator\Dns
    {
        return $this->getOperator('Dns');
    }

    public function dnsTemplate(): Operator\DnsTemplate
    {
        return $this->getOperator('DnsTemplate');
    }

    public function databaseServer(): Operator\DatabaseServer
    {
        return $this->getOperator('DatabaseServer');
    }

    public function mail(): Operator\Mail
    {
        return $this->getOperator('Mail');
    }

    public function certificate(): Operator\Certificate
    {
        return $this->getOperator('Certificate');
    }

    public function siteAlias(): Operator\SiteAlias
    {
        return $this->getOperator('SiteAlias');
    }

    public function ip(): Operator\Ip
    {
        return $this->getOperator('Ip');
    }

    public function eventLog(): Operator\EventLog
    {
        return $this->getOperator('EventLog');
    }

    public function secretKey(): Operator\SecretKey
    {
        return $this->getOperator('SecretKey');
    }

    public function ui(): Operator\Ui
    {
        return $this->getOperator('Ui');
    }

    public function servicePlan(): Operator\ServicePlan
    {
        return $this->getOperator('ServicePlan');
    }

    public function virtualDirectory(): Operator\VirtualDirectory
    {
        return $this->getOperator('VirtualDirectory');
    }

    public function database(): Operator\Database
    {
        return $this->getOperator('Database');
    }

    public function session(): Operator\Session
    {
        return $this->getOperator('Session');
    }

    public function locale(): Operator\Locale
    {
        return $this->getOperator('Locale');
    }

    public function logRotation(): Operator\LogRotation
    {
        return $this->getOperator('LogRotation');
    }

    public function protectedDirectory(): Operator\ProtectedDirectory
    {
        return $this->getOperator('ProtectedDirectory');
    }

    public function reseller(): Operator\Reseller
    {
        return $this->getOperator('Reseller');
    }

    public function resellerPlan(): Operator\ResellerPlan
    {
        return $this->getOperator('ResellerPlan');
    }

    public function aps(): Operator\Aps
    {
        return $this->getOperator('Aps');
    }

    public function servicePlanAddon(): Operator\ServicePlanAddon
    {
        return $this->getOperator('ServicePlanAddon');
    }

    public function site(): Operator\Site
    {
        return $this->getOperator('Site');
    }

    public function phpHandler(): Operator\PhpHandler
    {
        return $this->getOperator('PhpHandler');
    }
}
