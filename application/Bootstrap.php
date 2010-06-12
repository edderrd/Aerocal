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
     * Creates a Zend_Translate object using configuration paratemeters, and
     * also uses gettext as adapter
     *
     * @param array $config
     * @return Zend_Translate
     */
    protected function _createTranslate($config = array())
    {
        if (!empty($config)) {
            $path = realpath($config['path']) . "/%s/default.mo";

            $translate = new Zend_Translate(
                                'gettext',
                                sprintf($path, $config['default']),
                                $config['default'],
                                $config['options']
                         );

            foreach($config['languages'] as $lang) {
                if ($lang != $config['default']) {
                    $translate->addTranslation(sprintf($path, $lang), $lang);
                }
            }
            $translate->setLocale($config['default']);

            return $translate;
        }

        return false;
    }

    /**
     * Initialize translate using gettext
     *
     * @return Zend_Translate
     */
    protected function _initTranslate()
    {
        $translateConfig = $this->getOption('translate');
        $translate = $this->_createTranslate($translateConfig);

        $registry = Zend_Registry::getInstance();
        $registry->set('translate', $translate);

        return $translate;
    }

    /**
     * Initialize navigation helper
     *
     * @return Zend_Navigation
     */
    protected function _initNavigation()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/navigation.ini", APPLICATION_ENV);
        $navigation = new Zend_Navigation($config->navigation);
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

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );

        // translate
        $view->translate = $translate = Zend_Registry::get("translate");

        $viewRenderer->setView($view);
        // college default info parameters
        $config = $this->getOption('app');
        $config['environment']  = APPLICATION_ENV;
        $view->app = $config;
        $view->headTitle($config['header']);

        
        // Helpers
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->jQuery()->enable();
        $view->addHelperPath(APPLICATION_PATH . "/views/helpers/", "My_View_Helper");

        // Navigation
        $navigation = Zend_Registry::get('navigation');
        $view->navigation($navigation)->setTranslator($translate);

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

