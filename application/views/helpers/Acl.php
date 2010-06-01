<?php
/**
 * Access control list helper
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class My_View_Helper_Acl extends App_View_Helper_Abstract
{
    protected $identity;

    public function acl()
    {
        $this->identity = Zend_Auth::getInstance()->getIdentity();
        return $this;
    }

    public function isValid($controller, $action)
    {
        return $this->identity->isValidResource($controller, $action);
    }

    public function isAdmin()
    {
        return $this->identity->isAdmin;
    }
}
?>
