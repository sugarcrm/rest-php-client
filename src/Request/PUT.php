<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Request;

use SugarAPI\SDK\Request\Abstracts\AbstractRequest;

class PUT extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    protected static $_TYPE = 'PUT';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_HEADERS = array(
        "Content-Type: application/json"
    );

    /**
     * JSON Encode Body
     * @inheritdoc
     */
    public function setBody($body)
    {
        return parent::setBody(json_encode($body));
    }
}
