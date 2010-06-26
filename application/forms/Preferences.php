<?php
/**
 * User preferences Form
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class Form_Preferences
    extends App_Form
{
    public function init()
    {
        $this->setMethod("post");
        $this->setDefaultTranslator(Zend_Registry::get("translate"));

        $firstName = new Zend_Form_Element_Text("firstname");
        $firstName->setLabel("First name");
        $this->addElement($firstName);

        $lastName = new Zend_Form_Element_Text("lastname");
        $lastName->setLabel("Last name");
        $this->addElement($lastName);

        $language = new Zend_Form_Element_Select("language");
        $language->setLabel("Language")
                 ->setMultiOptions($this->_getLanguageList());
        $this->addElement($language);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Update");
        $this->addElement($submit);
    }
}
?>
