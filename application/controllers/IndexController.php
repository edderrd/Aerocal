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
        $calendar = new App_JqCalendar();
        $calendar->defaultView();
        $calendar->currentFirstDay(true);
        
        // add reservations only if the user have aircrafts
        
        if(count($user->Aircraft->toArray()) > 0)
            $calendar->enableAdd(true);
        else
            $calendar->enableAdd(false);

        if (Zend_Auth::getInstance()->getIdentity()->isAdmin)
        {
            $this->_addHeadTitle("All reservations");
            $reservations = Reservation::findAll();

            $this->view->events = Reservation::toEvents($reservations, true);
            $calendar->loadUrl("/index/index/format/json");
        }
        else
        {
            $this->_addHeadTitle("My reservations");
            $reservations = Reservation::findByUser($user->id);

            $this->view->events = Reservation::toEvents($reservations);
            $calendar->loadUrl("/index/index/format/json");
        }
        if (!$calendar->canAdd())
            $this->setMessage(self::$_translate->_("You don't have aircraft assigned"));
        
        $this->view->jqCalendar = $calendar;
    }

    public function reserveAction()
    {
        $form = new Form_Reserve();
        $submit = $this->_request->getParam("submit");
        $params = $this->_getAllParams();
        $user = Zend_Auth::getInstance()->getIdentity()->user;
        $subaction = isset($params['subaction']) ? $params['subaction'] : null;
        $this->view->subaction = $subaction;
        $this->view->title = self::$_translate->_("Add Reservation");
        
        switch($subaction)
        {
            case "submit":
                $params['user_id'] = $user->id;
                if (Reservation::isAvailable($params))
                {
                    Reservation::addReservation($params);
                    $this->view->redirect = "/index/index";
                    $this->view->message = self::$_translate->_("Reservation added");
                }
                else
                {
                    $this->view->message = self::$_translate->_("You reservation is not valid");
                    $buttons[self::$_translate->_("Close")]['action'] = "close";
                }
                break;
            default:
                $form->startDate->setValue(date("Y-m-d H:i" , strtotime($params['startDate'])));
                $form->endDate->setValue(date("Y-m-d H:i" , strtotime($params['endDate'])));
                $form->aircraft->setMultiOptions(App_Utils::toList($user->Aircraft, 'id', 'name'));
                $this->view->form = $form->toArray();
                $this->createAjaxButton("Add", "submit", $params, "/index/reserve/format/json/subaction/submit");
                break;
        }
    }
}