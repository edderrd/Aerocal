<?php

class My_View_Helper_ShowAircraft extends App_View_Helper_Abstract
{
    protected $_span = '<span %s>%s</span>&nbsp;';

    /**
     * Display preformated aircrafts
     * @param array $aircrafts
     * @param array $attributes
     * @return string
     */
    public function showAircraft($aircrafts, $attributes = array())
    {
        $translate = Zend_Registry::get("translate");
        if (empty($aircrafts))
        {
            return sprintf(
                $this->_span,
                $this->_parseAttributes($attributes),
                $translate->_("No aircraft assigned")
            );
        }

        $attributes['class'] = "button-option";
        $attributes = $this->_parseAttributes($attributes);
        $rv = "";
        foreach($aircrafts as $aircraft)
        {
            $aircraftName = $aircraft['AircraftType']['type'].": ".$aircraft['name'];
            $rv .= sprintf($this->_span, $attributes, $aircraftName);
        }

        return $rv;
    }
}

?>
