<?php

class IndexController extends App_Controller_Action
{
    public function init()
    {
        $this->_helper
             ->ajaxContext()
             ->addActionContexts(array("html", "json"))
             ->initContext();

        parent::init();

    }

    public function indexAction()
    {
        //$fc = new App_Fullcalendar();
        //$fc->selectable(false)->editable(false);

        $user = Zend_Auth::getInstance()->getIdentity()->user;
        if (Zend_Auth::getInstance()->getIdentity()->isAdmin)
        {
            $this->_addHeadTitle("All reservations");
            $this->view->reservations = Reservation::findAll();

            //$fc->addEvents(Reservation::toEvents($this->view->reservations, true));
        }
        else
        {
            $this->_addHeadTitle("My reservations");
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

