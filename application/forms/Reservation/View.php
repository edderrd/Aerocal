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
    }
}
?>
