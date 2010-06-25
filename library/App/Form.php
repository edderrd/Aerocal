<?php
/**
 * Zend_Form Extension
 *
 * @author Edder
 */
class App_Form extends Zend_Form
{

    /**
     * Convert form elements into a array
     * @return array
     */
    function toArray()
    {
        $rv = array();
        $rv['id'] = $this->getId();
        $rv['method'] = $this->getMethod();

        foreach ($this->getElements() as $element)
        {
            $rv['elements'][$element->getId()]["id"] = $element->getId();
            $rv['elements'][$element->getId()]["label"] = $element->getLabel();
            $rv['elements'][$element->getId()]["type"] = $element->getType();
            $rv['elements'][$element->getId()]["html"] = $element->render();
        }

        return $rv;
    }
}