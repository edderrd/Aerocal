<?php
class UserController extends App_Controller_Action
{
    protected $_contexts = array(
        'new' => array("html", "json"),
        'create' => array("html", "json")
    
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
        $this->_addHeadTitle("User preferences");
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
            $this->_redirect($this->baseUrl."/index/index");
        }
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        $this->view->form->firstname->setValue($user->first_name);
        $this->view->form->lastname->setValue($user->last_name);
        //TODO: Implement password change
        $this->view->form->language->setValue($user->language);
    }
    
    public function listAction()
    {
        $this->_addHeadTitle("User list");
        $this->view->users = User::findAll();
    }
	
    public function loginAction()
    {
        $this->_addHeadTitle("Login");
        $this->_helper->layout()->setLayout("login");
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
        $this->_addHeadTitle("Logout");
    	Zend_Auth::getInstance()->clearIdentity();
    	$this->_redirect("/");
    }
    
    public function createAction()
    {
        $form = new Form_User();
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
        		    User::createUser($params);
        		    
        		    $this->view->message = self::$_translate->_("User created correctly");
        		    $this->createAjaxButton("Close", "close");
        		    $this->view->redirect = $this->baseUrl."/user/list";
        		    break;
        		}        		        	
        		
        	default:
        	    $form->role_id->setMultiOptions(App_Utils::toList(AclRole::findAll(), 'id', 'name'));
                $form->aircraft->setMultiOptions(App_Utils::toList(Aircraft::findAll(), 'id', 'name'));
                
                $this->view->title = self::$_translate->_("Create user");
                $this->createAjaxButton("Create", "submit", $params, "/user/create/format/json/subaction/submit");
                $this->view->form = $form->toArray();
        }
        
        
    }
}
