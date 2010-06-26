<?php
/**
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class AircraftController extends App_Controller_Action
{

    protected $_contexts = array(
        'create' => array("html", "json")
    
    );

    public function preDispatch()
    {
        parent::preDispatch();
        // ajax context
        $this->_helper
                ->ajaxContext()
                ->addActionContexts($this->_contexts)
                ->initContext();
    }

    public function indexAction()
    {
        $this->_addHeadTitle("Aircraft list");
        $this->view->aircrafts = Aircraft::findAll();
    }
    
    public function createAction()
    {
        $form = new Form_Aircraft();
        $params = $this->_getAllParams();
        
        $form->type_id->setMultiOptions(App_Utils::toList(AircraftType::findAll(), 'id', 'type'));
        $form->status_id->setMultiOptions(App_Utils::toList(AircraftStatus::findAll(), 'id', 'status'));
        
        $this->view->title = self::$_translate->_("Create aircraft");
        $buttons[self::$_translate->_("Create")]['action'] = "submit";
        $buttons[self::$_translate->_("Create")]['url'] = "/index/index/format/json/subaction/submit";
        $buttons[self::$_translate->_("Create")]['params'] = $params;
        $this->view->buttons = $buttons;
        $this->view->form = $form->toArray();
    }
}
?>
