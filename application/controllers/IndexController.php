<?php

class IndexController extends App_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $fc = new App_FullCalendar();
        $this->view->calendarOptions = $fc->getConfig();
    }


}

