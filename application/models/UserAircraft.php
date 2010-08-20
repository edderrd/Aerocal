<?php

/**
 * UserAircraft
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class UserAircraft extends BaseUserAircraft
{

    /**
     * Delete user relations with the aircrafts assigned
     * @param int $userId
     * @return <type>
     */
    public static function deleteByUserId($userId)
    {
        return Doctrine_Query::create()
                ->delete("UserAircraft ua")
                ->addWhere("ua.user_id = ?", $userId)
                ->execute();
    }

}