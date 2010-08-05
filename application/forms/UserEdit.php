<?php
/**
 * @author Edder Rojas
 */
class Form_UserEdit extends Form_User
{
    public function init()
    {
        parent::init();

        // password element to a hidden one
        $this->removeElement("password");

        $this->addElement("hidden", "password", array(
            'required'   => false,
            'decorators' => array()
        ));
        $this->aircraft->setLabel("Aircraft assigned");
        
        $this->addElement("select", "aircraft_available", array(
            'required' => false,
            'label'     => "Aircraft Available",
            'multiple'  => 'multiple',
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->aircraft_available->setRegisterInArrayValidator(false);

        // reposition
        $language = $this->language;
        $this->removeElement("language");
        $this->addElement($language);

        $this->addElement("hidden", "user_id", array(
            'required'   => false,
            'decorators' => array()
        ));
    }
}
?>
