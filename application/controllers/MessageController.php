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
    	$user = Zend_Auth::getInstance()->getIdentity()->user;
    	$this->view->data = Message::findAllByUserId($user->id);
    }
    
    public function deleteAction()
    {
    	
    }
}
?>
