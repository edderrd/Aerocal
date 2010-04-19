<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

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
        //$view->addHelperPath(APPLICATION_PATH . "/views/helpers/", "My_View_Helper");
        
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

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(
            Doctrine::ATTR_MODEL_LOADING,
            Doctrine::MODEL_LOADING_CONSERVATIVE
        );
        $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);
        //get doctrine configuration section
        $doctrineConfig = $this->getOption('doctrine');

        Doctrine::loadModels($doctrineConfig['models_path']);
        $conn = Doctrine_Manager::connection($doctrineConfig['dsn'], 'doctrine');
        $conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);

        return $conn;
    }
}

