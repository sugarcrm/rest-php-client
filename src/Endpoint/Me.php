<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;


use MRussell\REST\Endpoint\ModelEndpoint;

/**
 * Me Endpoint provides access to current logged in user details
 * - Can view and update user
 * - Can view and update user preferences
 * - Can view followed records
 * @package Sugarcrm\REST\Endpoint
 * @method $this    preferences()
 * @method $this    savePreferences()
 * @method $this    preference(string $preference)
 * @method $this    createPreference(string $preference)
 * @method $this    updatePreference(string $preference)
 * @method $this    deletePreference(string $preference)
 * @method $this    following()
 */
class Me extends ModelEndpoint implements SugarEndpointInterface
{
    const MODEL_ACTION_VAR = 'action';

    const USER_ACTION_PREFERENCES = 'preferences';

    const USER_ACTION_SAVE_PREFERENCES = 'savePreferences';

    const USER_ACTION_GET_PREFERENCE = 'preference';

    const USER_ACTION_CREATE_PREFERENCE = 'createPreference';

    const USER_ACTION_UPDATE_PREFERENCE = 'updatePreference';

    const USER_ACTION_DELETE_PREFERENCE = 'deletePreference';

    const USER_ACTION_FOLLOWING = 'following';

    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => true,
        self::PROPERTY_HTTP_METHOD => "GET"
    );

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'me/$:action/$:actionArg1';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_SUGAR_USER_ACTIONS = array(
        self::USER_ACTION_PREFERENCES => "GET",
        self::USER_ACTION_SAVE_PREFERENCES => "PUT",
        self::USER_ACTION_GET_PREFERENCE => "GET",
        self::USER_ACTION_UPDATE_PREFERENCE => "PUT",
        self::USER_ACTION_CREATE_PREFERENCE => "POST",
        self::USER_ACTION_DELETE_PREFERENCE => "DELETE",
        self::USER_ACTION_FOLLOWING => "GET"
    );

    public function __construct(array $options = array(), array $properties = array())
    {
        parent::__construct($options, $properties);
        foreach(static::$_DEFAULT_SUGAR_USER_ACTIONS as $action => $method){
            $this->actions[$action] = $method;
        }
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest(){
        return $this->configureRequest($this->getRequest());
    }

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $options) {
        $action = $this->getCurrentAction();
        switch($action){
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