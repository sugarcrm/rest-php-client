<?php

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\Abstracts\AbstractModelEndpoint;

class Me extends AbstractModelEndpoint
{
    const USER_ACTION_PREFERENCES = 'preferences';

    const USER_ACTION_SAVE_PREFERENCES = 'savePreferences';

    const USER_ACTION_GET_PREFERENCE = 'preference';

    const USER_ACTION_UPDATE_PREFERENCE = 'updatePreference';

    protected static $_ENDPOINT_URL = 'me/$:action/$:actionarg1';

    protected $actions = array(
        self::USER_ACTION_PREFERENCES => JSON::HTTP_GET,
        self::USER_ACTION_SAVE_PREFERENCES => JSON::HTTP_PUT,
        self::USER_ACTION_GET_PREFERENCE => JSON::HTTP_GET,
        self::USER_ACTION_UPDATE_PREFERENCE => JSON::HTTP_PUT,
    );
}