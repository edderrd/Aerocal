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

    public function editAction()
    {
        $id = $this->_request->getParam("id");
        $data = Aircraft::findById($id);

        $form = new Form_AircraftEdit();
        $form->name->setValue($data['name']);
        $form->type_id->addMultiOptions(App_Utils::toList(AircraftType::findAll(), 'id', 'type'));
        $form->status_id->setMultiOptions(App_Utils::toList(AircraftStatus::findAll(), 'id', 'status'));
        $form->type_id->setValue($data['type_id']);
        $form->aircraft_id->setValue($id);

        $form->populate($data);

        $options = array(
            'title'     => "Edit aircraft",
            'url'       => "/aircraft/edit/format/json/subaction/submit",
            'button'    => "Edit",
            'success'   => array(
                "button" => array(
                    "title"  => "Close",
                    "action" => "close"
                ),
                "redirect" => "/aircraft/index",
                "message" => "Aircraft {$form->name->getValue} modified correctly"
            ),
            'model' => array(
                "class" => "Aircraft",
                "method" => "edit"
            )
        );
        $this->ajaxFormProcessor($form, $options);
    }
}
?>
