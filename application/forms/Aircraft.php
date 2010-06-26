<?php
/**
 * Aircraft Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Aircraft
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

        $this->addElement("select", "type_id", array(
            'required' => false,
            'label'     => "Type",
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->type_id->setRegisterInArrayValidator(false);
        
        $this->addElement("select", "status_id", array(
            'label'      => "Status",
            'required'   => true,
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->status_id->setRegisterInArrayValidator(false);
        
    }
}
?>
