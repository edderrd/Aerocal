<?php
/**
 * @author edder
 */
class Form_AircraftEdit extends Form_Aircraft
{
    public function init()
    {
        parent::init();

        $this->addElement("hidden", "aircraft_id", array(
            'required'   => false,
            'decorators' => array()
        ));
    }
}
?>
