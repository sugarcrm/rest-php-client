<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Request;

class POSTFile extends POST
{
    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_HEADERS = array(
        "Content-Type: multipart/form-data"
    );

    /**
     * Overrides POST setBody, so that Body is not json encoded
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->body = $body;
        $this->setOption(CURLOPT_POSTFIELDS, $this->body);
        return $this;
    }
}
