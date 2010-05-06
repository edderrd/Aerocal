<?php

class App_View_Helper_Abstract extends Zend_View_Helper_Abstract
{

    /**
     * Convert a relational array to html attributes
     *
     * @param array $attributes
     * @return string
     */
    protected function _parseAttributes($attributes)
    {
        $rv = "";
        if (!empty($attributes)) {
            if (!is_array($attributes)) {
                return $rv;
            }
            foreach($attributes as $key => $value) {
                $rv .= "$key='$value' ";
            }
        }

        return $rv;
    }
}