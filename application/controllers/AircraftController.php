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
        $subaction = isset($params['subaction']) ? $params['subaction'] : null;
        
        switch ($subaction)
        {
            case 'submit':
                if(!$form->isValid($params))
                {
                    $this->view->isValid = $form->isValid($params);
                    $this->view->message = $form->getErrorMessages();                   
                }
                else
                {
                    $this->view->isValid = $form->isValid($params);
                    Aircraft::create($params);
                    
                    $this->view->message = self::$_translate->_("Aircraft created correctly");
                    $this->createAjaxButton("Close", "close");
                    $this->view->redirect = $this->baseUrl."/aircraft/index";
                    break;
                }                           
                
            default:
                $form->type_id->setMultiOptions(App_Utils::toList(AircraftType::findAll(), 'id', 'type'));
                $form->status_id->setMultiOptions(App_Utils::toList(AircraftStatus::findAll(), 'id', 'status'));
                
                $this->view->title = self::$_translate->_("Create aircraft");
                $this->createAjaxButton("Create", "submit", $params, "/aircraft/create/format/json/subaction/submit");
                $this->view->form = $form->toArray();
        }
        
    }
}
?>
