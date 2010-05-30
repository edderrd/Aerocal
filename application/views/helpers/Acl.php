<?php
/**
 * Access control list helper
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class My_View_Helper_Acl extends App_View_Helper_Abstract
{

    public function acl()
    {
        return $this;
    }

    public function isValid($controller, $action)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        return $identity->isValidResource($controller, $action);
    }
}
?>
