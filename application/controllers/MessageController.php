<?php
/**
 * Message related controllers
 *
 * @author edder
 */
class MessageController extends App_Controller_Action 
{
	/**
	 * User's messages list
	 */
	public function indexAction()
    {
    	// enable active menu on view
    	$this->view->messagesActive = true;
    	
    	$params = $this->_getAllParams();
    	if (isset($params['subaction']))
    	{
    		if (isset($params['id']))
    			Message::markRead($params['id']);
    	}
    	
    	$user = Zend_Auth::getInstance()->getIdentity()->user;
    	$this->view->data = Message::findAllByUserId($user->id);
    }
    
    public function deleteAction()
    {
    	
    }
}
?>
