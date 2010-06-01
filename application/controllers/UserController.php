<?php
class UserController extends App_Controller_Action
{
    protected $_contexts = array(
        'new' => array("html", "json")
    );

    public function preDispatch()
    {
        parent::preDispatch();
        // ajax context
        $this->_helper
                ->ajaxContext()
                ->addActionContexts($this->_contexts)
                ->initContext();
    }

    public function indexAction()
    {
        $this->view->form = new Form_Preferences();

        if ($this->getRequest()->isPost() &&
                $this->view->form->isValid($this->_getAllParams()))
        {
            $user = Zend_Auth::getInstance()->getIdentity()->user;
            $user->first_name = $this->_request->getParam("firstname");
            $user->last_name = $this->_request->getParam("lastname");
            $user->language = $this->_request->getParam("language");
            $user->save();
            
            $this->setMessage(array(self::$_translate->_("Preferences saved successfully")));
            $this->_redirect("/index/index");
        }
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        $this->view->form->firstname->setValue($user->first_name);
        $this->view->form->lastname->setValue($user->last_name);
        //TODO: Implement password change
        $this->view->form->language->setValue($user->language);
    }
    
    public function listAction()
    {
        $this->view->users = User::findAll();
    }
	
    public function loginAction()
    {
        $this->view->form = new Form_Login();
        if ($this->getRequest()->isPost() &&
            $this->view->form->isValid($this->_getAllParams()))
        {
            $adapter = new App_Auth_Adapter($this->_getParam('username'), $this->_getParam("password"));
            $result = $adapter->authenticate();
            $result = Zend_Auth::getInstance()->authenticate($adapter);

            if (Zend_Auth::getInstance()->hasIdentity())
            {
                $this->_redirect('/index/index');
            }
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
