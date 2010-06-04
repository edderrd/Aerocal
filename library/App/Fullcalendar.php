<?php

require_once dirname(__FILE__) . "/Fullcalendar/Options.php";

/**
 * Configuration generator for FullCalendar JQuery plugin
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class App_Fullcalendar
{

    /**
     * @var App_Fullcalendar_Options
     */
    public $options = null;

    public function __construct(App_Fullcalendar_Options $options = null)
    {
        if(is_null($options))
            $this->options = new App_Fullcalendar_Options();
        else
            $this->options = $options;
    }
}
?>
