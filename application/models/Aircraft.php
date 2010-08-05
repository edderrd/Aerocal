<?php

/**
 * Aircraft
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Aircraft extends BaseAircraft
{

    /**
     * Get all aircraft with his types
     * @param $filter array
     * @return array
     */
    public static function findAll($filter = array())
    {
        $r = Doctrine_Query::create()
                    ->from("Aircraft a")
                    ->leftJoin("a.AircraftType t")
                    ->leftJoin("a.AircraftStatus s");

        if (isset($filter['exclude']) && !empty($filter['exclude']))
            $r->andWhere('a.id not in ('. implode(",", $filter['exclude']) .')');
        
        return $r->fetchArray(true);
    }

    /**
     * Create a aircraft
     * @param array $params
     * @return mixed
     */
    public static function create($params)
    {
        if(!empty($params))
        {
            $aircraft = new Aircraft();
            $aircraft->name = $params['name'];
            $aircraft->type_id = $params['type_id'];
            $aircraft->status_id = $params['status_id'];
            $aircraft->save();
            $aircraft->refresh();
            
            return $aircraft->id;
        }
        
        return false;
    }

    /**
     * Return all aircraft by array of ids
     * @param array $aircrafts
     * @return array
     */
    public static function findIn($aircrafts)
    {
        if (!empty($aircrafts))
        {
            $r = Doctrine_Query::create()
                    ->from("Aircraft a")
                    ->addWhere("a.id in (".implode(",", $aircrafts).")")
                    ->fetchArray(true);
            
            return $r;
        }
        else
        {
            return array();
        }
    }
}