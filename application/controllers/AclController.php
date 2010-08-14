<?php
/**
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class AclController extends App_Controller_Action
{

    public function indexAction()
    {
        $this->_addHeadTitle("Permissions");
        $this->view->permissions = AclPermission::findAll();
        $this->view->resources = AclResource::findAll();
        $this->view->roles = AclRole::findAll();
    }
    
    public function createroleAction()
    {
        $form = new Form_AclRole();
        $form->resources->setMultiOptions(App_Utils::toList(AclResource::findAll(), 'id', 'description'));
        
        $options = array(
            'title'     => "Create role",
            'url'       => "/acl/create_role/format/json/subaction/submit",
            'button'    => "Create",
            'success'   => array(
                "button" => array(
                    "title"  => "Close",
                    "action" => "close"
                ),
                "redirect" => "/acl/index",
                "message" => "Role created correctly"
            ),
            'model' => array(
                "class" => "AclRole",
                "method" => "create"
            )
        );
        
        $this->ajaxFormProcessor($form, $options);                
    }
}
?>
