<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use ArrayAccess;
use GuzzleHttp\Psr7\Response;
use MRussell\REST\Endpoint\Abstracts\AbstractCollectionEndpoint;
use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\Interfaces\CollectionInterface;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;
use Sugarcrm\REST\Endpoint\Data\FilterData;

/**
 * Provides access to the Audit API for a given Module
 * - Works with a single Module Bean type
 * - Tracks pagination
 * @package Sugarcrm\REST\Endpoint
 */
class ModuleAudit extends AbstractSugarBeanCollectionEndpoint
{
    protected static $_ENDPOINT_URL = '$module/$id/audit';
}
