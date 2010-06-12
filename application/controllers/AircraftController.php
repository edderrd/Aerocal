<?php
/**
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class AircraftController extends App_Controller_Action
{


    public function indexAction()
    {
        $this->_addHeadTitle("Aircraft list");
        $this->view->aircrafts = Aircraft::findAll();
    }
}
?>
