<?php

class IndexController extends App_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $start = date("Y-m-d H:i:s", time());
        $end = date("Y-m-d H:i:s", strtotime("+1hour", time()));
        
        $fc = new App_FullCalendar();
        $fc->addEvent("Hello World", $start, $end);


        $this->view->calendarOptions = $fc->getConfig();
    }


}

