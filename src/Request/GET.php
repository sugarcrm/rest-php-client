<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Request;

use SugarAPI\SDK\Request\Abstracts\AbstractRequest;

class GET extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    protected static $_TYPE = 'GET';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_HEADERS = array(
        "Content-Type: application/json"
    );

    /**
     * @inheritdoc
     *
     * Convert Body to Query String
     */
    public function setBody($body)
    {
        if (is_array($body) || is_object($body)) {
            $body = http_build_query($body);
        }
        $this->body = $body;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * Configure the URL with Body since Payload is sent via Query String
     */
    public function send()
    {
        $body = '';
        if (!empty($this->body)) {
            if (strpos($this->url, "?") === false) {
                $body = "?".$this->body;
            } else {
                $body = "&".$this->body;
            }
        }
        $this->setURL($this->url.$body);
        return parent::send();
    }
}
