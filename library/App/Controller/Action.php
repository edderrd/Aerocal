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
                    $msg = $this->_translate->_("You dont have permission to browse");
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
     * Add messages to the view
     * @param array $messages
     */
    public function setMessage($messages)
    {
        $messages = is_array($messages) ? $messages : array($messages);
        $notification = new Zend_Session_Namespace("notification");
        $notification->messages = $messages;
    }

    public function init()
    {
        self::$_translate = Zend_Registry::get("translate");
    }

    public function preDispatch()
    {
        $this->_validateSession();
    }
}