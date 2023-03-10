<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Auth;

use MRussell\REST\Auth\Abstracts\AbstractOAuth2Controller;
use MRussell\REST\Auth\AuthControllerInterface;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Client\PlatformAwareInterface;
use Sugarcrm\REST\Client\PlatformAwareTrait;
use Sugarcrm\REST\Client\SugarApi;

/**
 * The Authentication Controller for the Sugar 7 REST Client
 * - Manages authenticating to API
 * - Manages refreshing API token for continuous access
 * - Manages logout
 * - Configures Endpoints that require auth, so that Requests are properly formatted
 * @package Sugarcrm\REST\Auth
 */
class SugarOAuthController extends AbstractOAuth2Controller
{
    public const ACTION_SUGAR_SUDO = 'sudo';

    public const OAUTH_PROP_PLATFORM = 'platform';

    protected static $_AUTH_HEADER = 'OAuth-Token';

    protected static $_DEFAULT_GRANT_TYPE = self::OAUTH_RESOURCE_OWNER_GRANT;

    protected static $_DEFAULT_SUGAR_AUTH_ACTIONS = array(
        self::ACTION_SUGAR_SUDO
    );

    /**
     * @inheritdoc
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => 'sugar',
        'client_secret' => '',
        self::OAUTH_PROP_PLATFORM => SugarApi::PLATFORM_BASE
    );

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        foreach (static::$_DEFAULT_SUGAR_AUTH_ACTIONS as $action) {
            $this->actions[] = $action;
        }
    }

    /**
     * @inheritdoc
     */
    protected function getAuthHeaderValue(): string
    {
        return $this->getTokenProp('access_token');
    }

    /**
     * @inheritDoc
     */
    public function getCacheKey(): string
    {
        if (empty($this->cacheKey)) {
            $this->cacheKey = sha1($this->generateUniqueCacheString($this->getCredentials()));
        }
        return $this->cacheKey;
    }

    /**
     * @param array $creds
     * @return string
     */
    protected function generateUniqueCacheString(array $creds): string
    {
        $key = '';
        try {
            $ep = $this->getActionEndpoint(self::ACTION_AUTH);
            if ($ep->getClient()) {
                $key = $ep->getClient()->getServer();
            } else {
                $key = $ep->getBaseUrl();
            }
        } catch (\Exception $ex) {
            $this->getLogger()->info("Cannot use server in cache string.");
        }

        if (!empty($creds['client_id'])) {
            $key .= "_".$creds['client_id'];
        }
        if (!empty($creds['platform'])) {
            $key .= "_".$creds['platform'];
        }
        if (!empty($creds['username'])) {
            $key .= "_".$creds['username'];
        }
        if (!empty($creds['sudo'])) {
            $key .= "_"."sudo".$creds['sudo'];
        }
        return ltrim($key, "_");
    }

    /**
     * Refreshes the OAuth 2 Token
     * @param $user string
     * @return bool
     */
    public function sudo($user): bool
    {
        $accessToken = $this->getTokenProp('access_token');
        $return = false;
        if (!empty($accessToken)) {
            try {
                $Endpoint = $this->configureSudoEndpoint($this->getActionEndpoint(self::ACTION_SUGAR_SUDO), $user);
                $response = $Endpoint->execute()->getResponse();
                if ($response->getStatusCode() == 200) {
                    $creds = $this->getCredentials();
                    $creds['sudo'] = $user;
                    $this->setCredentials($creds);
                    $this->parseResponseToToken(self::ACTION_SUGAR_SUDO, $response);
                    $return = true;
                }
            } catch (\Exception $ex) {
                $this->getLogger()->error("Exception Occurred sending SUDO request: ".$ex->getMessage());
            }
        }
        return $return;
    }

    /**
     * Configure the Sudo Endpoint
     * @param EndpointInterface $Endpoint
     * @param $user
     * @return EndpointInterface
     */
    protected function configureSudoEndpoint(EndpointInterface $Endpoint, $user): EndpointInterface
    {
        $Endpoint->setUrlArgs(array($user));
        $data = array();
        $creds = $this->getCredentials();
        $data['platform'] = $creds['platform'];
        $data['client_id'] = $creds['client_id'];
        $Endpoint->setData($data);
        return $Endpoint;
    }
}
