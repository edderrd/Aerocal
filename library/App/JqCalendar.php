<?php

require_once dirname(__FILE__) . "/JqCalendar/Options.php";

/**
 * Configuration generator for Calendar JQuery plugin
 *
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class App_JqCalendar
{

    /**
     * @var App_JqCalendar_Options
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
     * @param App_JqCalendar_Options $options
     */
    public function __construct(App_JqCalendar_Options $options = null)
    {
        if(is_null($options))
            $this->options = new App_JqCalendar_Options();
        else
            $this->options = $options;
    }

    /**
     * Add a array of events to calendar options
     * @param array $events
     * @return App_JqCalendar
     */
    public function addEvents($events)
    {
        if (!empty($events))
        {
            foreach($events as $event)
            {
                $this->options->addEvent($event);
            }
        }

        return $this;
    }

    /**
     * Set default view
     * @param string $option
     * @return App_JqCalendar
     */
    public function defaultView($option = 'week')
    {
        $this->options->setOption("view", $option);
        return $this;
    }

    /**
     * Enable or disable add events
     * @param bool $option
     * @return App_JqCalendar
     */
    public function enableAdd($option)
    {
        if($option)
        {
            $this->options->setOption("canAdd", $option);
        }
        else
        {
            $this->options->setOption("canAdd", $option);
            $this->options->setOption("readonly", true);
        }
        return $this;
    }

    /**
     * Load url in json
     * @param string $url
     * @return App_JqCalendar
     */
    public function loadUrl($url)
    {
        $this->options->setOption("url", $url);
        $this->options->setOption("autoload", true);

        return $this;
    }

    /**
     * Is enable to add events
     * @return <type>
     */
    public function canAdd()
    {
        return $this->options->getOption("canAdd");
    }
}
?>
