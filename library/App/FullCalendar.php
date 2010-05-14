<?php
/**
 * Configuration generator for FullCalendar JQuery plugin
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class App_FullCalendar
{

    protected $_data = array();

    /**
     * Class constructor, set defaults
     */
    public function __construct()
    {
        //defaults
        $this->setHeaders("prev,next today", "title", "agendaWeek, month, agendaDay");
        $this->setOption("editable", true);
        $this->_data['events'] = array();
    }

    /**
     * Set calendar headers
     * options (prev, next, today, title, moth, agendaWeek, agendaDay)
     * @param string $left
     * @param string $center
     * @param string $right
     * @return App_FullCalendar
     */
    public function setHeaders($left = "today", $center = "title", $right = "month")
    {
        // left header
        if(!empty($left))
            $this->_data['header']['left'] = $left;

        // center
        if(!empty($center))
            $this->_data['header']['center'] = $center;
        
        // right header
        if(!empty($right))
            $this->_data['header']['right'] = $right;

        return $this;
    }

    /**
     * Create a new event
     * @param string $title
     * @param string $start
     * @param string $end
     * @param bool $allDay
     * @param string $url
     * @return App_FullCalendar
     */
    public function addEvent($title, $start = "", $end = "", $allDay = false, $url = "")
    {

        if (empty($title))
            throw new Exception("Cannot add a event with empty title");

        if (empty($start) && $allDay == false)
            throw new Exception("Cannot add a event with empty start date");

        $event = array();

        if (!empty($start))
            $event['start'] = $start;

        if (!empty($end))
            $event['end'] = $end;

        if (!empty($url))
            $event['url'] = $url;

        $event['allDay'] = $allDay;
        $event['title'] = $title;


        $this->_data['events'][] = $event;

        return $this;
    }

    /**
     * Set a option in a calendar
     * @param string $name
     * @param mixed $value
     * @return App_FullCalendar
     */
    public function setOption($name, $value)
    {
        if(empty($name))
            throw new Exception("Cannot set a empty option");

        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Return all fullcalendar configuration and events
     * @param boolean $inJson default true
     * @return mixed
     */
    public function getConfig($inJson = true)
    {
        if ($inJson)
            return json_encode($this->_data);
        else
            return $this->_data;
    }

}
?>
