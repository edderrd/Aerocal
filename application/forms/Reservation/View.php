<?php
/**
 * Reservation view details form
 *
 * @author edder
 */
class Form_Reservation_View extends App_Form
{
    public function init()
    {
        $translate = Zend_Registry::get("translate");
        $this->setDefaultTranslator($translate);

        $this->addElement("text", "start_date", array(
            'label'     => "Start date",
            'decorators' => array("ViewHelper"),
            'disabled'   => 'disabled'
        ));

        $this->addElement("text", "end_date", array(
            'label'     => "End date",
            'decorators' => array("ViewHelper"),
            'disabled'   => 'disabled'
        ));

        $this->addElement("text", "aircraft", array(
            'required' => false,
            'label'     => "Aircraft",
            'decorators' => array("ViewHelper", "Errors"),
            'disabled'   => 'disabled'
        ));

        $this->addElement("text", "status", array(
            'required'   => false,
            'label'      => "Status",
            'decorators' => array("ViewHelper", "Errors"),
            'disabled'   => 'disabled'
        ));
    }
}
?>
