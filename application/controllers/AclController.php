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

    public function editroleAction()
    {
        $id = $this->_request->getParam("id");
        $data = AclRole::findById($id);
        $roleResources = App_Utils::toList($data['AclPermission']['AclResource'], "id", "description");

        $form = new Form_AclRoleEdit();
        $form->description->setValue($data['description']);
        $form->resources->setMultiOptions($roleResources);
        $form->resources_available->setMultiOptions(App_Utils::toList(AclResource::findAll(array('exclude' => array_keys($roleResources))), 'id', 'description'));
        $form->name->setValue($data['name']);
        $form->aclrole_id->setValue($id);

        $form->populate($data);

        $options = array(
            'title'     => "Edit role",
            'url'       => "/acl/edit_role/format/json/subaction/submit",
            'button'    => "Edit",
            'success'   => array(
                "button" => array(
                    "title"  => "Close",
                    "action" => "close"
                ),
                "redirect" => "/acl/index",
                "message" => "Role {$form->name->getValue} modified correctly"
            ),
            'model' => array(
                "class" => "AclRole",
                "method" => "edit"
            )
        );
        $this->ajaxFormProcessor($form, $options);
    }
}
?>
