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
        //$fc = new App_Fullcalendar();
        //$fc->selectable(false)->editable(false);

        $user = Zend_Auth::getInstance()->getIdentity()->user;
        if (Zend_Auth::getInstance()->getIdentity()->isAdmin)
        {
            $this->view->reservations = Reservation::findAll();
            //$fc->addEvents(Reservation::toEvents($this->view->reservations, true));
        }
        else
        {
            $this->view->reservations = Reservation::findByUser($user->id);
            //$fc->addEvents(Reservation::toEvents($this->view->reservations));
        }

        //$this->view->fc = $fc;
    }

    public function reserveAction()
    {
        //TODO: Add logic here!
    }
}

