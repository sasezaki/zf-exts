<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public $frontController;

    protected $_logger;
    protected $_resourceLoader;


    protected function _initFrontControllerSettings()
    {
        $this->bootstrap('frontController');
        $http = new Zenmondo_Controller_Request_Http;
        $this->frontController->setRequest($http);
        $this->frontController->throwExceptions(true);
    }

}

