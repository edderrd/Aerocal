<?php
/**
 * AclRoleEdit Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_AclRoleEdit extends Form_AclRole
{
    public function init()
    {
        parent::init();

        $this->resources->setLabel("Resources assigned")
                        ->setRequired(false);


        $this->addElement("select", "resources_available", array(
            'required' => false,
            'label'     => "Resources available",
            'multiple'  => 'multiple',
            'decorators' => array("ViewHelper", "Errors")
        ));
        $this->resources_available->setRegisterInArrayValidator(false);

        // reposition
        $descriptionElement = $this->description;
        $this->removeElement("description");
        $this->addElement($descriptionElement);

        $this->addElement("hidden", "aclrole_id", array(
            'required'   => false,
            'decorators' => array()
        ));
    }
}