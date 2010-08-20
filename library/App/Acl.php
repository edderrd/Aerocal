<?php
/**
 * Description of Acl
 *
 * @author edder
 */
class App_Acl extends Zend_Acl
{

    /**
     * @var Zend_Acl_Role
     */
    protected $_role;
    /**
     * @var User
     */
    public $user;
    /**
     * @var boolean
     */
    public $isAmin = false;
    /**
     * @var string
     */
    protected $_defaultAction = null;

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
                if ($this->user['AclRole']['name'] == $rol['name'])
                {
                    $this->_role = new Zend_Acl_Role($rol['name']);
                    $this->addRole($this->_role);
                    continue;
                }

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
            $this->add(new Zend_Acl_Resource("mvc:default.user.login"));
            $this->add(new Zend_Acl_Resource("mvc:default.user.logout"));
        }        
        else
        {
            throw new Exception("There no Resources defined");
        }
        
        // default action
        $this->add(new Zend_Acl_Resource($this->_defaultAction));
    }

    /**
     * Setup permission over user property
     */
    protected function _addPermissions()
    {
        $user = $this->user->toArray(true);
        foreach($this->user['AclRole']['AclPermission']['AclResource'] as $resource)
        {
            $this->allow($this->user['AclRole']['name'], $resource['name']);
        }

        // allow default action
        $this->allow($this->user['AclRole']['name'], $this->_defaultAction);
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

        $config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini", APPLICATION_ENV);
        $this->_defaultAction = $config->app->defaultAction;
        $this->user = $user;
        $this->isAdmin = $user['AclRole']['name'] == "administrator" ? true : false;
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
        $resource = "mvc:default.$controller.$action";
        if ($this->isAllowed($this->user['AclRole']['name'], $resource))
            return true;
        else
            return false;
    }

    /**
     * Return user role
     * @return mixed
     */
    public function getRole()
    {
        if(!empty($this->_role))
            return $this->_role;
        return null;
    }

}
