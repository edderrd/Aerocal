<?php

class IndexController extends App_Controller_Action
{
    protected $_actionContexts = array(
        'reserve' => array("html", "json"),
        'index' => array("html", "json")
    );

    public function init()
    {
        $this->_helper
             ->ajaxContext()
             ->addActionContexts($this->_actionContexts)
             ->initContext();

        parent::init();

    }

    public function indexAction()
    {
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        if (Zend_Auth::getInstance()->getIdentity()->isAdmin)
        {
            $this->_addHeadTitle("All reservations");
            $reservations = Reservation::findAll();

            $this->view->events = Reservation::toEvents($reservations, true);
        }
        else
        {
            $this->_addHeadTitle("My reservations");
            $reservations = Reservation::findByUser($user->id);
            
            $this->view->events = Reservation::toEvents($reservations);
        }

        //$this->view->fc = $fc;
    }

    public function reserveAction()
    {
        $this->view->form = new Form_Reserve();
        $submit = $this->_request->getParam("submit");
        $params = $this->_getAllParams();
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        
        if ($submit)
        {
            $params['user_id'] = $user->id;
            Reservation::addReservation($params);
            $this->setMessage(self::$_translate->_("Reservation added"));
            $this->_redirect("/index/index");
        }
        else
        {
            $this->view->form->startDate->setValue(date("Y-m-d H:i:s" , strtotime($params['startDate'])));
            $this->view->form->endDate->setValue(date("Y-m-d H:i:s" , strtotime($params['endDate'])));
            $this->view->form->aircraft->setMultiOptions(App_Utils::toList($user->Aircraft, 'id', 'name'));
        }
        
    }
}