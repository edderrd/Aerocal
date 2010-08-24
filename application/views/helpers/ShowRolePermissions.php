<?php
/**
 * Display pre-formated permissions using a data from Db->doctrine
 *
 * @author edder
 */
class My_View_Helper_ShowRolePermissions extends App_View_Helper_Abstract
{
    protected $_span = '<span %s>%s</span>&nbsp;';

    public function showRolePermissions($permissions, $attributes = array())
    {
        $translate = Zend_Registry::get("translate");
        if (empty($permissions['AclResource']))
        {
            return sprintf(
                $this->_span,
                $this->_parseAttributes($attributes),
                $translate->_("No permissions assigned")
            );
        }

        $attributes['class'] = "button-option";
        $attributes = $this->_parseAttributes($attributes);
        $rv = "";
        foreach($permissions['AclResource'] as $permission)
        {
            $content = $permission['description'];
            $rv .= sprintf($this->_span, $attributes, $content);
        }

        return $rv;
    }
}
?>
