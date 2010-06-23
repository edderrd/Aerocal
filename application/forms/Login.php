<?php
/**
 * Login form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Login
    extends App_Form
{
    public function init()
    {
        $this->setDefaultTranslator(Zend_Registry::get("translate"));

        $elements = array();
        $this->setMethod("post");

        $username = new Zend_Form_Element_Text("username");
        $username->setLabel("Name")->setRequired(true);
        $elements[] = $username;

        $password = new Zend_Form_Element_Password("password");
        $password->setLabel("Password")->setRequired(true);
        $elements[] = $password;

        $submit = new Zend_Form_Element_Submit("login");
        $elements[] = $submit;

        $this->addElements($elements);
    }
}
?>
