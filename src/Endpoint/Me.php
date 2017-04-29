<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\Abstracts\AbstractModelEndpoint;

class Me extends AbstractModelEndpoint
{
    const MODEL_ACTION_VAR = 'action';

    const USER_ACTION_PREFERENCES = 'preferences';

    const USER_ACTION_SAVE_PREFERENCES = 'savePreferences';

    const USER_ACTION_GET_PREFERENCE = 'preference';

    const USER_ACTION_CREATE_PREFERENCE = 'createPreference';

    const USER_ACTION_UPDATE_PREFERENCE = 'updatePreference';

    const USER_ACTION_DELETE_PREFERENCE = 'deletePreference';

    const USER_ACTION_FOLLOWING = 'following';

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'me/$:action/$:actionarg1';

    /**
     * @inheritdoc
     */
    protected $actions = array(
        self::USER_ACTION_PREFERENCES => JSON::HTTP_GET,
        self::USER_ACTION_SAVE_PREFERENCES => JSON::HTTP_PUT,
        self::USER_ACTION_GET_PREFERENCE => JSON::HTTP_GET,
        self::USER_ACTION_UPDATE_PREFERENCE => JSON::HTTP_PUT,
        self::USER_ACTION_CREATE_PREFERENCE => JSON::HTTP_POST,
        self::USER_ACTION_DELETE_PREFERENCE => JSON::HTTP_DELETE,
        self::USER_ACTION_FOLLOWING => JSON::HTTP_GET
    );

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $options) {
        $action = $this->action;
        switch($this->action){
            case self::USER_ACTION_SAVE_PREFERENCES:
                $action = self::USER_ACTION_PREFERENCES;
                break;
            case self::USER_ACTION_UPDATE_PREFERENCE:
            case self::USER_ACTION_DELETE_PREFERENCE:
            case self::USER_ACTION_CREATE_PREFERENCE:
                $action = self::USER_ACTION_GET_PREFERENCE;
                break;
            case self::MODEL_ACTION_DELETE:
            case self::MODEL_ACTION_UPDATE:
            case self::MODEL_ACTION_CREATE:
            case self::MODEL_ACTION_RETRIEVE:
                $action = NULL;
                break;
        }
        if ($action !== NULL){
            $options[self::MODEL_ACTION_VAR] = $action;
        } else {
            if (isset($options[self::MODEL_ACTION_VAR])){
                unset($options[self::MODEL_ACTION_VAR]);
            }
        }
        return parent::configureURL($options);
    }

    /**
     * @inheritdoc
     */
    protected function configureAction($action,array $arguments = array()) {
        if (!empty($arguments)){
            switch($action){
                case self::USER_ACTION_GET_PREFERENCE:
                case self::USER_ACTION_UPDATE_PREFERENCE:
                case self::USER_ACTION_DELETE_PREFERENCE:
                case self::USER_ACTION_CREATE_PREFERENCE:
                    if (isset($arguments[0])){
                        $this->options['actionArg1'] = $arguments[0];
                    }
            }
        }
        parent::configureAction($action);
    }
}