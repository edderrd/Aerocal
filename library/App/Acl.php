<?php
/**
 * Description of Acl
 *
 * @author edder
 */
class App_Acl extends Zend_Acl
{

    /**
     * @var User
     */
    public $user;

    /**
     * Add roles to self ACL
     *
     * @param array $roles
     */
    protected function _addRoles($roles)
    {
        if(!empty($roles))
        {

            foreach($roles as $rol)
            {
                //TODO: Implement inheritance
                $this->addRole(new Zend_Acl_Role($rol['name']));
            }
        }
        else
        {
            throw new Exception("There no roles defined");
        }
    }

    /**
     * Add Resoureces to self ACL
     *
     * @param array $resources
     */
    protected function _addResources($resources)
    {
        if(!empty($resources))
        {

            foreach($resources as $resource)
            {
                $this->add(new Zend_Acl_Resource($resource['name']));
            }
            $this->add(new Zend_Acl_Resource("user:logout"));
        }
        else
        {
            throw new Exception("There no Resources defined");
        }
    }

    /**
     * Setup permission over user property
     */
    protected function _addPermissions()
    {
        if ($this->user['AclRole']['name'] == "administrator")
        {
            $this->allow($user['AclRole']['name']);
        }
        else
        {
            foreach($this->user['AclRole']['AclPermission']['AclResource'] as $resource)
            {
                $this->allow($this->user['AclRole']['name'], $resource['name']);
            }
            
        }
    }

    /**
     * Automatically setup roles, resources and setup permissions by
     * given user
     * @param User $user
     */
    public function __construct($user)
    {
        if(!$user)
            throw new Exception("There is a error on Roles and permissions");

        $this->user = $user;

        $this->_addRoles(AclRole::findAll());
        $this->_addResources(AclResource::findAll());
        $this->_addPermissions();
    }

    /**
     * Validate if the current resource is allowed by the user
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isValidResource($controller, $action)
    {
        $resource = "$controller:$action";
        if ($this->isAllowed($this->user['AclRole']['name'], $resource))
            return true;
        else
            return false;
    }

}
