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
        $form->type_id->setMultiOptions(App_Utils::toList(AircraftType::findAll(), 'id', 'type'));
        $form->status_id->setMultiOptions(App_Utils::toList(AircraftStatus::findAll(), 'id', 'status'));

        $options = array(
            'title'     => "Create aircraft",
            'url'       => "/aircraft/create/format/json/subaction/submit",
            'button'    => "Create",
            'success'   => array(
                "button" => array(
                    "title"  => "Close",
                    "action" => "close"
                ),
                "redirect" => "/aircraft/index",
                "message" => "Aircraft created correctly"
            ),
            'model' => array(
                "class" => "Aircraft",
                "method" => "create"
            )
        );
        $this->ajaxFormProcessor($form, $options);
    }
}
?>
