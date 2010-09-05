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
                    $this->view->redirect = $this->baseUrl."/index/index";
                    $this->view->message = self::$_translate->_("Reservation added");
                }
                else
                {
                    $this->view->message = self::$_translate->_("You reservation is not valid");
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
        $reservation = Reservation::findById($params["reservation_id"]);
        $this->view->content = array(
            'id' => 'testing',
            'elements' => array("html" => "<h1>{$params['title']}</h1>")
        );
        $this->view->title = $this->_("Reservation"). ": ". $reservation['Aircraft']['name'];
        $this->createAjaxButton("Close", "close");
        $this->createAjaxButton("Cancel reservation", "cancel");
    }
}