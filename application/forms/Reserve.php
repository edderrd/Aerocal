<?php
/**
 * User preferences Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Reserve
    extends Zend_Form
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

        $this->addElement("submit", "submit", array(
            'decorators' => array("ViewHelper")
        ));
    }
}
?>
