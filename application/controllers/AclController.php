<?php
/**
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class AclController extends App_Controller_Action
{

    public function indexAction()
    {
        $this->view->permissions = AclPermission::findAll();
        $this->view->resources = AclResource::findAll();
    }
}
?>
