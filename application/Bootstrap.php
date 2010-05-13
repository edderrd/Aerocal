<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAppAutoload()
    {
        $moduleLoad = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH
        ));
    }

    /**
     * Initialize navigation helper
     *
     * @return Zend_Navigation
     */
    protected function _initNavigation()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/navigation.ini", APPLICATION_ENV);
        $navigationConfig = $config->toArray();

        $navigation = new Zend_Navigation($navigationConfig['navigation']);
        Zend_Registry::set("navigation", $navigation);
    }


    /**
     * Bootstrapping view
     *
     * @return Zend_View
     */
    protected function _initView()
    {
        // initialize view
        $view = new Zend_View();
        $view->doctype("XHTML1_STRICT");
        $view->headTitle("Aerocal");

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );

        $viewRenderer->setView($view);
        // college default info parameters
        $config = $this->getOption('app');
        $config['environment']  = APPLICATION_ENV;
        $view->app = $config;
        
        // Helpers
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->jQuery()->enable();
        $view->addHelperPath(APPLICATION_PATH . "/views/helpers/", "My_View_Helper");

        // Navigation
        $navigation = Zend_Registry::get('navigation');
        $view->navigation($navigation);
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    /**
     * Boostrapping Doctrine orm with model autoloading
     *
     * @return Doctrine_Connection
     */
    protected function _initDoctrine()
    {
        $this->getApplication()->getAutoloader()
             ->pushAutoloader(array('Doctrine', 'autoload'));
        spl_autoload_register(array('Doctrine', 'modelsAutoload'));

        //get doctrine configuration section
        $doctrineConfig = $this->getOption('doctrine');
        
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING,
                               $doctrineConfig['model_autoloading']);
        $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        Doctrine::loadModels($doctrineConfig['models_path']);
        
        $conn = Doctrine_Manager::connection($doctrineConfig['dsn'], 'doctrine');
        $conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);

        return $conn;
    }
}

