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
            $this->setMessage(array("Save not implemented Yet!"));
            var_dump($this->_getAllParams());
        }
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        $this->view->form->firstname->setValue($user->first_name);
        $this->view->form->lastname->setValue($user->last_name);
        $this->view->form->roleid->setValue($user->role_id);
        //TODO: Implement password change
        $this->view->form->password->setValue($user->password);
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
