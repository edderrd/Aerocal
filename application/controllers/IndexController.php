<?php

class IndexController extends App_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $start = date("Y-m-d H:i:s", time());
        $end = date("Y-m-d H:i:s", strtotime("+1hour", time()));
        
        $fc = new App_FullCalendar();
        $fc->addEvent(self::$_translate->_("Hello World"), $start, $end);

        $this->view->calendarOptions = $fc->getConfig();

        $user = Zend_Auth::getInstance()->getIdentity()->user;
        $this->view->reservations = Reservation::findByUser($user->id);
    }
}

