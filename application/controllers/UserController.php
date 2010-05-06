<?php
class UserController extends App_Controller_Action
{

	public function indexAction()
	{
		$this->_redirect("/user/login");
	}
	
    public function loginAction()
    {
    	if ($this->getRequest()->isPost())
    	{
    		$adapter = new App_Auth_Adapter($this->_getParam('username'), $this->_getParam("password"));
    		$result = $adapter->authenticate();
    		$result = Zend_Auth::getInstance()->authenticate($adapter);
    		 if (Zend_Auth::getInstance()->hasIdentity())
                $this->_redirect('/index/index');
            else
                $this->view->messages = $result->getMessages();
    	}
    }
    
    public function logoutAction()
    {
    	Zend_Auth::getInstance()->clearIdentity();
    	$this->_redirect("/");
    }
}