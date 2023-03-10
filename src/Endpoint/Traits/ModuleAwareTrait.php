<?php

namespace Sugarcrm\REST\Endpoint\Traits;

use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint;

/**
 * Default implementation for managing the current module on a given endpoint
 * @package Sugarcrm\REST\Endpoint\Traits
 */
trait ModuleAwareTrait
{
    /**
     * @var string
     */
    protected $_beanName = '';

    public function getModule(): string
    {
        return $this->_beanName;
    }

    /**
     * @param $module string
     * @return $this
     */
    public function setModule(string $module)
    {
        $this->_beanName = $module;
        return $this;
    }

    /**
     * Alter the URL Args array to set the Module Var
     * @param array $urlArgs
     * @return array
     */
    protected function configureModuleUrlArg(array $urlArgs): array
    {
        if (isset($urlArgs[0])) {
            $urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG] = $urlArgs[0];
            unset($urlArgs[0]);
        }
        if (isset($urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG]) && $this->getModule() != $urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG]) {
            $this->setModule($urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG]);
        }
        if (!isset($urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG]) && !empty($this->getModule())) {
            $urlArgs[AbstractSugarBeanEndpoint::BEAN_MODULE_URL_ARG] = $this->getModule();
        }
        return $urlArgs;
    }
}
