<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Response;

use SugarAPI\SDK\Response\Abstracts\AbstractResponse;

class JSON extends AbstractResponse
{
    /**
     * Get JSON Response
     */
    public function getJson()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function getBody($asArray = true)
    {
        return json_decode($this->body, $asArray);
    }
}
