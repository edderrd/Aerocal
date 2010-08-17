<?php

/**
 * User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends BaseUser
{
    const WRONG_PW = 1;
    const NOT_FOUND = 2;

    /**
     * Perform authenticatino of a user
     * @param string $username
     * @param string $password
     * @return Model_User
     */
    public static function authenticate($username, $password)
    {
        $user = Doctrine_Query::create()
                    ->from("User u")
                    ->leftJoin("u.AclRole r")
                    ->leftJoin("r.AclPermission p")
                    ->leftJoin("p.AclResource re")
                    ->leftJoin("p.AclResource pre")
                    ->leftJoin("u.Aircraft a")
                    ->addWhere("u.username = '$username'")
                    ->fetchOne();

        if ($user)
        {
            if ($user->password == $password)
                return $user;

            throw new Exception(self::WRONG_PW);
        }

        throw new Exception(self::NOT_FOUND);
    }
    
    public static function findAll()
    {
        return Doctrine_Query::create()
                    ->from("User u")
                    ->leftJoin("u.AclRole r")
                    ->leftJoin("u.Aircraft a")
                    ->leftJoin("a.AircraftType at")
                    ->fetchArray(true);
    }
    
    /**
     * Create a user and his aircraft relations
     * @param array $params
     */
    public static function createUser($params)
    {
        if(!empty($params))
        {
            $user = new User();
            $user->first_name = $params['first_name'];
            $user->last_name = $params['last_name'];
            $user->role_id = $params['role_id'];
            $user->username = $params['username'];
            $user->password = $params['password'];
            if (!empty($params['aircraft']))
            {
                foreach($params['aircraft'] as $aircraft_id)
                {
                    $user->Aircraft[] = Doctrine::getTable("Aircraft")->find($aircraft_id);
                }
            }
            $user->language = $params['language'];
            $user->save();
            $user->refresh();
            
            return $user->id;
        }
    }

    /**
     * Look for a user by his id
     * @param int $id
     * @return array
     */
    public static function findById($id)
    {
        return Doctrine_Query::create()
                ->from("User u")
                ->leftJoin("u.Aircraft a")
                ->addWhere("id = $id")
                ->fetchOne()
                ->toArray();
    }

    public static function edit($params)
    {
        if (!empty($params))
        {
            $user = Doctrine::getTable("User")->findOneById($params['user_id']);
            $user->id = $params['user_id'];
            $user->first_name = $params['first_name'];
            $user->last_name = $params['last_name'];
            $user->role_id = $params['role_id'];
            $user->language = $params['language'];
            
            if (!empty($params['aircraft']) || !empty($params['aircraft_available']))
            {
                $params['aircraft'] = isset($params['aircraft']) ? $params['aircraft'] : array();
                $params['aircraft_available'] = isset($params['aircraft_available']) ? $params['aircraft_available'] : array();

                Aircraft::deleteUserAircrafts($user->id, $params['aircraft']);
                
                foreach($params['aircraft_available'] as $aircraft)
                    $user->Aircraft[] = Doctrine::getTable("Aircraft")->findOneById($aircraft);
            }
            $user->save();
            return $user->id;
        }
        return false;
    }
}