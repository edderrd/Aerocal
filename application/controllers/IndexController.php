<?php

class IndexController extends App_Controller_Action
{

    public function init()
    {
        parent::init();

        $this->_helper
             ->ajaxContext()
             ->addActionContexts(array("html", "json"))
             ->initContext();
    }

    public function indexAction()
    {
        $start = date("Y-m-d H:i:s", time());
        $end = date("Y-m-d H:i:s", strtotime("+1hour", time()));
        
        $fc = new App_Fullcalendar();
        $fc->options->addEvent(self::$_translate->_("Hello World"), $start, $end);
        $this->view->fc = $fc;

        $user = Zend_Auth::getInstance()->getIdentity()->user;
        if (Zend_Auth::getInstance()->getIdentity()->isAdmin)
            $this->view->reservations = Reservation::findAll();
        else
            $this->view->reservations = Reservation::findByUser($user->id);
    }

    public function reserveAction()
    {
        //TODO: Add logic here!
    }
}

