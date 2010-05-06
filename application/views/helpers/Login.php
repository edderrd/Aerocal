<?php

/**
 * Display a link depending if the user is logged in or not
 * @author Edder Rojas <edder.rojas@gmail.com>
 * @version 1.0
 */
class My_View_Helper_Login extends App_View_Helper_Abstract
{
	/**
	 * @var string
	 */
    protected $_html = '<span %s>%s <a href="%s">%s</a></span>';
	
	/**
	 * Contruct method
	 * @param $attributes array
	 * @return string
	 */
	public function login($attributes = array())
	{
		$content = "";
		$url = "";
		
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $message = "Welcome Back, {$identity->first_name} {$identity->last_name} |";
            $content = " Logout";
            $url = $this->view->url(array("action" => "logout", "controller" => "user"));
        }
        else
        {
        	$content =  "Login";
        	$message = "Not logged in | ";
        	$url = $this->view->url(array("action" => "login", "controller" => "user"));
        }
        
        return sprintf(
                       $this->_html,
                       $this->_parseAttributes($attributes),
                       $message,
                       $url,
                       $content);
	}
}