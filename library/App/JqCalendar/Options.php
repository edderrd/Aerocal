<?php

class App_JqCalendar_Options
{
    protected $_data = array();

    /**
     * Class constructor, set defaults
     */
    public function __construct()
    {
        //EditCmdhandler:Edit,
        //DeleteCmdhandler:Delete,
        //ViewCmdhandler:,
        //onWeekOrMonthToDay:wtd,
        //onBeforeRequestData: cal_beforerequest,
        //onAfterRequestData: cal_afterrequest,
        //onRequestDataError: cal_onerror,
        //quickAddUrl: "/index/reserve/format/json",
        //quickUpdateUrl: DATA_FEED_URL + "?method=update",
        //quickDeleteUrl: DATA_FEED_URL + "?method=remove"

        $this->_data['eventItems'] = array();
        //defaults
        $this->setOption("view", "week")
             ->setOption("weekstartday", 1)
             ->setOption("theme", 3);
    }

    
    /**
     * Create a new event
     * @param array $event
     * @param string $url
     * @return App_JqCalendar_Options
     */
    public function addEvent($event)
    {
        $this->_data['eventItems'][] = $event;

        return $this;
    }

    /**
     * Set a option in a calendar
     * @param string $name
     * @param mixed $value
     * @return App_JqCalendar_Options
     */
    public function setOption($name, $value)
    {
        if(empty($name))
            throw new Exception("Cannot set a empty option");

        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Return all calendar configuration and events
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

    /**
     * Return a option if is set
     * @param string $option
     * @return mixed
     */
    public function getOption($option)
    {
        if (isset($this->_data[$option]))
            return $this->_data[$option];
        return null;
    }
}
