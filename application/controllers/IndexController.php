<?php

class IndexController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
    }
    
    public function preDispatch()
    {
        parent::preDispatch();
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
            $calendar->loadUrl($this->baseUrl."/index/index/format/json");
        }
        else
        {
            $this->_addHeadTitle("My reservations");
            $reservations = Reservation::findByUser($user->id);

            $this->view->events = Reservation::toEvents($reservations);
            $calendar->loadUrl($this->baseUrl."/index/index/format/json");
        }
        
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
                $this->view->isValid = true;
                $params['user_id'] = $user->id;
                if (Reservation::isAvailable($params))
                {
                    Reservation::addReservation($params);
                    $this->reservationNotifyAdmins($user, $params);
//                    $this->view->redirect = $this->baseUrl."/index/index";
                    $this->setMessage($this->_("Reservation added"));
                }
                else
                {
                    $this->setMessage($this->_("You reservation is not valid"));
                    $this->createAjaxButton("Close", "close");
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
        $this->view->messages = $this->getMessages();
    }

    public function reservationNotifyAdmins($user, $params)
    {
        $subject = self::$_translate->_("Reservation added");
        $msg = self::$_translate->_("User");
        $msg .= " {$user->first_name} {$user->last_name} ";
        $msg .= self::$_translate->_("made a reservation on");
        $msg .= " " . $params['startDate'];

        Message::notifyAllAdmins($user->id, $subject, $msg);
    }

    /**
     * View details action
     */
    public function viewAction()
    {
        $params = $this->_getAllParams();
        $identity = Zend_Auth::getInstance()->getIdentity();
        $reservation = Reservation::findById($params["reservation_id"]);
        $form = new Form_Reservation_View();

        $form->start_date->setValue(App_Utils::formatDate($reservation['start_date']));
        $form->end_date->setValue(App_Utils::formatDate($reservation['end_date']));
        $form->aircraft->setValue($reservation['Aircraft']['name']);
        $form->status->setValue($reservation['ReservationStatus']['status']);
        
        $this->view->content = array(
            'id' => 'testing',
            'elements' => array("html" => "<h1>{$params['title']}</h1>")
        );
        $this->view->title = $this->_("Reservation"). ": ". $reservation['Aircraft']['name'];

        if (strtolower($reservation['ReservationStatus']['status']) == 'accepted' && $identity->isValidResource('index', 'cancel'))
            $this->createAjaxButton("Cancel reservation", "custom", null, $this->baseUrl."/index/cancel/format/json/id/{$reservation['id']}");
        else
            $this->createAjaxButton("Close", "close");

        $this->view->form = $form->toArray();
    }

    /**
     * Cancel a reservation action
     * receive a id param
     */
    public function cancelAction()
    {
        $id = $this->_request->getParam('id');
        $this->view->title = $this->_("Reservation");

        if (empty($id))
            $this->setMessage($this->_("Empty given reservation id"));
        else
        {
            $this->setMessage($this->_("Reservation cancelled"));

            $reservation = Reservation::findById($id);
            $user = Zend_Auth::getInstance()->getIdentity()->user;

            Reservation::cancelById($id);
            Message::notifyCancelReservation($reservation, $user->id);
        }
        $this->view->messages = $this->getMessages();
    }
}