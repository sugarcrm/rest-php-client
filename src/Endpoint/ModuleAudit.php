<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;

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
