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
            'decorators' => array("ViewHelper")
        ));

        $this->addElement("text", "last_name", array(
            'label'      => "Last Name",
            'required'   => true,
            'decorators' => array("ViewHelper")
        ));
        
        $this->addElement("text", "username", array(
            'label'      => "Username",
            'required'   => true,
            'decorators' => array("ViewHelper")
        ));
        
        $this->addElement("password", "password", array(
            'label'      => "Password",
            'required'   => true,
            'decorators' => array("ViewHelper")
        ));
        
        $this->addElement("select", "role_id", array(
            'required' => true,
            'label'     => "Role",
            'decorators' => array("ViewHelper")
        ));

        $this->addElement("select", "aircraft", array(
            'required' => false,
            'label'     => "Aircraft",
            'decorators' => array("ViewHelper")
        ));
        
        $this->addElement("select", "language", array(
            'required' => false,
            'label'     => "Aircraft",
            'decorators' => array("ViewHelper")
        ));
        
        $this->language->setMultiOptions($this->_getLanguageList());
    }
}
?>
