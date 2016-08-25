<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Request\Abstracts;

use SugarAPI\SDK\Request\Interfaces\RequestInterface;

abstract class AbstractRequest implements RequestInterface
{
    const STATUS_INIT = 'initialized';
    const STATUS_SENT = 'sent';
    const STATUS_CLOSED = 'closed';

    /**
     * The HTTP Request Type
     * @var string
     */
    protected static $_TYPE = '';

    /**
     * The Default Curl Options
     * @var array
     */
    protected static $_DEFAULT_OPTIONS = array(
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HEADER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Sugar-REST-PHP-Client'
    );

    /**
     * The default HTTP Headers to be added to Curl Request
     * @var array
     */
    protected static $_DEFAULT_HEADERS = array();

    /**
     * The Curl Resource used to actually send data to Sugar API
     * @var - Curl Resource
     */
    protected $CurlResponse;

    /**
     * The raw response from curl_exec
     * @var - Curl Response
     */
    protected $CurlRequest;

    /**
     * List of Headers for Request
     * @var array
     */
    protected $headers = array();

    /**
     * The body of the request or payload. JSON Encoded
     * @var string
     */
    protected $body = '';

    /**
     * The URL the Request is sent to
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $status = '';

    /**
     * The Request Type
     * @var
     */
    protected $type;

    /**
     * The options configured on the Curl Resource object
     * @var array
     */
    protected $options = array();

    public function __construct($url = null)
    {
        $this->start();
        if (!empty($url)) {
            $this->setURL($url);
        }
        $this->setType(static::$_TYPE);
        $this->setHeaders(static::$_DEFAULT_HEADERS);
        foreach (static::$_DEFAULT_OPTIONS as $option => $value) {
            $this->setOption($option, $value);
        }
    }

    /**
     * Always make sure to destroy Curl Resource
     */
    public function __destruct()
    {
        if ($this->status !== self::STATUS_CLOSED) {
            curl_close($this->CurlRequest);
        }
    }

    /**
     * @inheritdoc
     */
    public function setURL($url)
    {
        $this->url = $url;
        $this->setOption(CURLOPT_URL, $this->url);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function addHeader($name, $value)
    {
        $token = $name.": ".$value;
        $this->headers[] = $token;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHeaders(array $array = array())
    {
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) {
                    $this->headers[] = $value;
                } else {
                    $this->addHeader($key, $value);
                }
            }
        }
        $this->setOption(CURLOPT_HTTPHEADER, $this->headers);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->body = $body;
        $this->setOption(CURLOPT_POSTFIELDS, $this->body);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function getCurlObject()
    {
        return $this->CurlRequest;
    }

    /**
     * @inheritdoc
     */
    public function setOption($option, $value)
    {
        curl_setopt($this->CurlRequest, $option, $value);
        $this->options[$option] = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $this->setHeaders();
        $this->CurlResponse = curl_exec($this->CurlRequest);
        $this->status = self::STATUS_SENT;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurlResponse()
    {
        return $this->CurlResponse;
    }

    /**
     * @inheritdoc
     */
    public function setType($type)
    {
        $this->type = strtoupper($type);
        $this->configureType();
        return $this;
    }

    /**
     * Configure the Curl Options based on Request Type
     */
    protected function configureType()
    {
        switch ($this->type) {
            case 'POST':
                $this->setOption(CURLOPT_POST, true);
                break;
            case 'DELETE':
            case 'PUT':
                $this->setOption(CURLOPT_CUSTOMREQUEST, $this->type);
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        if (gettype($this->CurlRequest) == 'resource') {
            $this->close();
        }
        $this->start();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function start()
    {
        $this->CurlRequest = curl_init();
        $this->status = self::STATUS_INIT;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        curl_close($this->CurlRequest);
        unset($this->CurlRequest);
        $this->status = self::STATUS_CLOSED;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurlStatus()
    {
        return $this->status;
    }
}
