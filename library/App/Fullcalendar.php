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

    /**
     * Correct Date format
     *
     * @param string $date
     * @return string
     */
    protected function _correctDate($date)
    {
        $format = "Y-m-d H:i:m:s";
        return date($format, strtotime($date));
    }

    /**
     * Costructor method
     * @param App_Fullcalendar_Options $options
     */
    public function __construct(App_Fullcalendar_Options $options = null)
    {
        if(is_null($options))
            $this->options = new App_Fullcalendar_Options();
        else
            $this->options = $options;
    }

    /**
     * Add a array of events to calendar options
     * @param array $events
     * @return App_Fullcalendar
     */
    public function addEvents($events)
    {
        if (!empty($events))
        {
            foreach($events as $event)
            {
                $this->options->addEvent(
                        $event['title'],
                        $this->_correctDate($event['start_date']),
                        $this->_correctDate($event['end_date']),false);
            }
        }

        return $this;
    }

    /**
     * Set option editable true|false
     * @param bool $option
     */
    public function editable($option)
    {
        $this->options->setOption("editable", $option);
        return $this;
    }

    /**
     * Set option selectable true|false
     * @param bool $option
     */
    public function selectable($option)
    {
        $this->options->setOption("selectable", $option);
        $this->options->setOption("selectHelper", $option);
        return $this;
    }
}
?>
