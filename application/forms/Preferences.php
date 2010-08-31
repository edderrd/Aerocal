<?php
/**
 * User preferences Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Preferences
    extends Form_User
{
    public function init()
    {
        parent::init();

        $this->addElement("hidden", "user_id", array(
            'required'   => false,
            'decorators' => array()
        ));

        $this->removeElement("aircraft");
        $this->removeElement("password");
        $this->removeElement("username");
        $this->removeElement("role_id");
    }
}
?>
