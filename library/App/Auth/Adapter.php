<?php

class App_Auth_Adapter 
    implements Zend_Auth_Adapter_Interface
{

	const NOT_FOUND_MSG = "User not valid";
	const BAD_PASSWORD_MSG = "Username or password invalid";
        const USER_DISABLED = "User is disabled";
	
	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var password
	 */
	protected $_username = "";
	
	/**
	 * @var string
	 */
	protected $_password = "";
	
	
	public function __construct($username, $password)
	{
	   $this->_username = $username;
	   $this->_password = $password;
	}

	
	public function authenticate()
	{
		try
		{
		  $user = User::authenticate($this->_username, $this->_password);
                  $this->user = new App_Acl($user);

		  return $this->result(Zend_Auth_Result::SUCCESS);	
		} 
		catch (Exception $e)
		{
                        if ($e->getMessage() == User::DISABLED)
                            return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::USER_DISABLED);
                        
			if ($e->getMessage() == User::WRONG_PW)
			    return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PASSWORD_MSG);
			
			if ($e->getMessage() == User::NOT_FOUND)
			    return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MSG);
		}
	}
	
	/**
	 * Creates a Zend_Auth_Result
	 * 
	 * @param $code int Zend_Auth_Result code
	 * @param $messages array
	 * @return Zend_Auth_Result 
	 */
	private function result($code, $messages = array())
	{
		$messages = is_array($messages) ? $messages : array($messages);
		
		return new Zend_Auth_Result($code, $this->user, $messages);
	}
	
}