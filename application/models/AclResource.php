<?php

/**
 * AclResource
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class AclResource extends BaseAclResource
{

    /**
     * Get all AclResources
     * @param array filter
     * @return array
     */
    public static function findAll($filter = array())
    {
        $r = Doctrine_Query::create()
                    ->from("AclResource r");

        if (isset($filter['exclude']) && !empty($filter['exclude']))
            $r->andWhere('r.id not in ('. implode(",", $filter['exclude']) .')');

        return $r->fetchArray(true);
    }

}