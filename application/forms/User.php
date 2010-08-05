<?php
/**
 * User preferences Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_User
    extends App_Form
{
    public function init()
    {
        $translate = Zend_Registry::get("translate");
        $this->setDefaultTranslator($translate);

        $this->addElement("text", "first_name", array(
            'label'      => "First Name",
            'required'   => true,
            'focus'      => 'focus',
            'decorators' => array("ViewHelper", "Errors")
        ));

        $this->addElement("text", "last_name", array(
            'label'      => "Last Name",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors")
        ));
        
        $this->addElement("text", "username", array(
            'label'      => "Username",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors")
        ));
        
        $this->addElement("password", "password", array(
            'label'      => "Password",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors")
        ));
        
        $this->addElement("select", "role_id", array(
            'required' => false,
            'label'     => "Role",
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->role_id->setRegisterInArrayValidator(false);

        $this->addElement("select", "aircraft", array(
            'required' => false,
            'label'     => "Aircraft",
            'multiple'  => 'multiple',
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->aircraft->setRegisterInArrayValidator(false);
        
        $this->addElement("select", "language", array(
            'required' => false,
            'label'     => "Language",
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->language->setRegisterInArrayValidator(false);
        
        $this->language->setMultiOptions($this->_getLanguageList());
    }
}
?>
