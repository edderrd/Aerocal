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
        
        // add reservations only if the user have aircrafts
        $this->view->canAddReservation = false;
        if(count($user->Aircraft->toArray()) > 0)
            $this->view->canAddReservation = true;

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
                $buttons[self::$_translate->_("Add")]['action'] = "submit";
                $buttons[self::$_translate->_("Add")]['url'] = "/index/reserve/format/json/subaction/submit";
                $buttons[self::$_translate->_("Add")]['params'] = $params;
                $this->view->buttons = $buttons;
                break;
        }
    }
}