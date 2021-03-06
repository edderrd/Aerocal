<?php

class App_Controller_Action extends Zend_Controller_Action
{
	
    /**
     * Login controller -> action exception
     * @var array
     */
    protected $loginException = array(
       'user' => array(
           'login',
           'logout'
       )
    );
    
    /**
     * @var Zend_Translate
     */
    protected static $_translate = null;
    /**
     * @var App_Acl
     */
    protected static $_acl;
    /**
     * @var array
     */
    protected $_defaultContextFormat = array("html", "json");
    /**
     * @var String
     */
    public $baseUrl;

    /**
     * Check if the user is currently logged in
     * @return bool
     */
    protected function _isUserLoggedIn()
    {
        if (Zend_Auth::getInstance()->hasIdentity())
            return true;
        else
            return false;
    }

    /**
     * Translate function
     * @param string $msg
     * @return string
     */
    protected function _($msg)
    {
        return self::$_translate->_("$msg");
    }

    /**
     * Validate if the current controller -> action is a exception
     * @param string $controller
     * @param string $action
     * @param bool
     */
    protected function _isActionException($controller, $action)
    {
            $exceptedControllers = array_keys($this->loginException);

            if (in_array($controller, $exceptedControllers))
            {
                    if (in_array($action, $this->loginException[$controller]))
                        return true;
            }
            return false;
    }
    
    /**
     * Validate if the user has logged in, and if user have permission
     * otherwise will redirect respectivelly
     */
    protected function _validateSession()
    {
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();

        // validate if the user is logged in
        if(!$this->_isUserLoggedIn())
        {
            if (!$this->_isActionException($controller, $action))
                $this->_redirect("/user/login");
        }
        else
        {
            
            // validate if the user have permissions to see this action
            $identity = Zend_Auth::getInstance()->getIdentity();
            if(!$identity->isValidResource($controller, $action))
            {
                // will redirect if isn't a action exception
                if (!$this->_isActionException($controller, $action))
                {
                    $msg = self::$_translate->_("You dont have permission to browse this page");
                    $this->setMessage($msg . " $controller\\$action");
                    $this->_redirect("/index/index");
                }
            }
            // set navigation acl
            $acl = Zend_Auth::getInstance()->getIdentity();
            $this->view->navigation()->setDefaultAcl($acl);
            $this->view->navigation()->setRole($acl->getRole());
            $translate = Zend_Registry::get("translate");
            $translate->setLocale($acl->user->language);
            Zend_Registry::set("translate", $translate);
        }
    }

    /**
     * Update permissions on given acl permission
     */
    protected function updatePermissions()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $identity->UpdatePermissions();
        $this->view->navigation()->setDefaultAcl($identity);
        $this->view->navigation()->setRole($acl->getRole());
    }

    /**
     * Add a translated message to headtitle
     * @param string $msg
     * @return App_Controller_Action
     */
    protected function _addHeadTitle($msg)
    {
        $separator = " - ";
        $msg = self::$_translate->_($msg);
        $this->view->headTitle($separator . $msg);

        return $this;
    }
    
    /**
     * Add messages to the view
     * @param array $messages
     */
    public function setMessage($messages)
    {
        $messages = is_array($messages) ? $messages : array($messages);
        $notification = new Zend_Session_Namespace("notification");
        foreach($messages as $message)
            $notification->messages[] = $message;
    }

    /**
     * Get All messages
     * @return array
     */
    public function getMessages()
    {
        $notification = new Zend_Session_Namespace("notification");
        $messages = $notification->messages;
        $notification->messages = array();

        return $messages;

    }

    public function init()
    {
        self::$_translate = Zend_Registry::get("translate");
        // setup baseurl
        $this->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->view->baseUrl = $this->baseUrl;
    }

    public function preDispatch()
    {
        $this->_validateSession();
        $action = $this->_request->getActionName();
        $context[$action] = $this->_defaultContextFormat; 
        $this->_helper
                ->ajaxContext()
                ->addActionContexts($context)
                ->initContext();
    }
    
    /**
     * Create modal dialog action button
     
     * @param string $name
     * @param string $action
     * @param array $params
     * @param string $url
     * @return App_Controller_Action;
     */
    public function createAjaxButton($name, $action, $params = null, $url = null)
    {
        $this->view->buttons = empty($this->view->buttons) ? array() : $this->view->buttons;
        $button = array();
        $name = self::$_translate->_($name);
        
        $button[$name]['action'] = $action;
        
        if(!empty($params))
            $button[$name]['params'] = $params;

        if(!empty($url))
            $button[$name]['url'] = $this->baseUrl.$url;
            
        $this->view->buttons = array_merge($this->view->buttons, $button);
        
        return $this;
    }
    
    /**
     * Create Modal dialog business logic
     * @param App_Form $form
     * @param array $options
     */
    public function ajaxFormProcessor(App_Form $form, $options)
    {
        $params = $this->_getAllParams();
        $subaction = isset($params['subaction']) ? $params['subaction'] : null;
        
        switch ($subaction)
        {
            case 'submit':
                if(!$form->isValid($params))
                {
                    $this->view->isValid = $form->isValid($params);
                    $this->view->message = $form->getErrorMessages();                   
                }
                else
                {
                    $this->view->isValid = $form->isValid($params);
                    $modelClass = $options['model']['class'];
                    $modelMethod = $options['model']['method'];
                    // persist method
                    call_user_func(array($modelClass, $modelMethod), $params);
                    
                    $this->setMessage(self::$_translate->_($options['success']['message']));
                    $this->createAjaxButton(
                        $options['success']['button']['title'], 
                        $options['success']['button']['action']);
                        
                    if (isset($options['success']['redirect']))    
                        $this->view->redirect = $this->baseUrl.$options['success']['redirect'];
                    break;
                }
                
            default:
                $this->view->title = self::$_translate->_($options['title']);
                $this->createAjaxButton(self::$_translate->_($options['button']), "submit", $params, $options['url']);
                $this->view->form = $form->toArray();
        }
    }
}