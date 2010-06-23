<?php
/**
 * User preferences Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Reserve
    extends App_Form
{
    public function init()
    {
        $translate = Zend_Registry::get("translate");
        $this->setDefaultTranslator($translate);

        $this->addElement("hidden", "startDate", array(
            'decorators' => array("ViewHelper")
        ));

        $this->addElement("hidden", "endDate", array(
            'decorators' => array("ViewHelper")
        ));

        $this->addElement("select", "aircraft", array(
            'required' => false,
            'label'     => "Aircraft",
            'decorators' => array("ViewHelper", "Errors")
        ));
    }
}
?>
