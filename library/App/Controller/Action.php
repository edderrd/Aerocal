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
	       'logout',
	       'index'
	   )
	);
	
	/**
	 * Check if the user is currently logged in
	 * @return bool
	 */
	protected function checkLoggedIn()
	{
		if (Zend_Auth::getInstance()->hasIdentity())
		{
		    return true;
		}
		else 
		{
            $this->view->messages = array("You haven't logged in");
            return false;	
		}
	}
	
	/**
	 * Validate if the current controller -> action is a exception
	 * @param string $controller
	 * @param string $action
	 * @param bool
	 */
	protected function isLoginExceptedAction($controller, $action)
	{
		$exceptedControllers = array_keys($this->loginException);
		
		if (in_array($controller, $exceptedControllers))
		{
			if (in_array($action, $this->loginException[$controller]))
			    return true;
		}
		return false;
	}
	
	
	public function preDispatch()
	{
		$action = $this->getRequest()->getActionName();
		$controller = $this->getRequest()->getControllerName();

		if (!Zend_Auth::getInstance()->hasIdentity()) {
		    if (!$this->isLoginExceptedAction($controller, $action))
		        $this->_redirect("/user/login");
		}
	}
	
}