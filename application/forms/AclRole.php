<?php
/**
 * AclRole Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_AclRole
    extends App_Form
{
    public function init()
    {
        $translate = Zend_Registry::get("translate");
        $this->setDefaultTranslator($translate);

        $this->addElement("text", "name", array(
            'label'      => "Name",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors")
        ));
        
        $this->addElement("select", "resources", array(
            'label'      => "Resources",
            'required'   => true,
            'multiple'   => "multiple",
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->resources->setRegisterInArrayValidator(false);
        
        $this->addElement("textarea", "description", array(
            'label'      => "Description",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors"),
            'cols'       => "25",
            'rows'       => "4",
        ));
    }
}