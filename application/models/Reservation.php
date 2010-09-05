<?php

/**
 * Reservation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Reservation extends BaseReservation
{
    /**
     * Return a color number to be used on calendar.jquery plugin
     * @param int $statusId
     * @return int
     */
    protected static function _getColorByStatus($statusId)
    {
        switch ($statusId)
        {
            case 1:
                return 6; // blue
            case 2:
                return 1; //red
            default:
                return 8; // dunno
        }
    }

    /**
     * Return all data
     * @param bool $now
     * @return array
     */
    public static function findAll($now = true)
    {
        $date = date("Y-m-d G:i:s", time());

        $r = Doctrine_Query::create()
                    ->from("Reservation r")
                    ->leftJoin("r.User u")
                    ->leftJoin("r.Aircraft a")
                    ->leftJoin("a.AircraftType at")
                    ->leftJoin("r.ReservationStatus s");

        if ($now)
            $r->addWhere("r.end_date > '$date'");

        return $r->fetchArray(true);
    }

    /**
     * Get all reservation by a user id
     * @param int $userId
     * @return array
     */
    public static function findByUser($userId)
    {
        $now = date("Y-m-d G:i:s", time());

        return Doctrine_Query::create()
                    ->from("Reservation r")
                    ->leftJoin("r.User u")
                    ->leftJoin("r.Aircraft a")
                    ->leftJoin("a.AircraftType at")
                    ->leftJoin("r.ReservationStatus s")
                    ->addWhere("u.id = $userId")
                    ->addWhere("r.end_date > '$now'")
                    ->fetchArray(true);
    }

    /**
     * Converts reservations array into jquery.calendar events
     * @param array $reservations
     * @param bool addNames add names to title?
     * @return array
     */
    public static function toEvents($reservations, $addName = false)
    {
        $translate = Zend_Registry::get("translate");
        $rv = array();

        if (!empty($reservations))
        {
            foreach($reservations as $reservation)
            {
                $tmp = array();

                $tmp["id"] = $reservation['id'];
                $status = $reservation['status_id'] == 2 ? "<strong>{$translate->_("Cancelled")}</strong><br>" : "";
                $tmp['title'] = $status;
                if($addName)
                    $tmp['title'] .= "{$reservation['User']['first_name']} {$reservation['User']['last_name']}<br>";

                $tmp['title'] .= "<span class='".strtolower($reservation['Aircraft']['AircraftType']['type'])."-icon'>&nbsp;</span>";
                $tmp['title'] .= "{$reservation['Aircraft']['name']}";
                $tmp['start_date'] = date("m/d/Y H:i:s", strtotime($reservation['start_date']));
                $tmp['end_date'] = date("m/d/Y H:i:s", strtotime($reservation['end_date']));
                $tmp['is_all_day'] = 0;
                $tmp['recurrent'] = 0;
                $tmp['relation'] = 0;
                $tmp['color'] = self::_getColorByStatus($reservation['status_id']);
                $tmp['extra'] = 0;
                $tmp['location'] = "";

                $rv[] = array_values($tmp);
            }
        }
        return $rv;
    }

    /**
     * Add a new reservation
     *
     * @param array $data
     * @return int
     */
    public static function addReservation($data)
    {
        if (!empty($data))
        {
            $r = new Reservation();
            $r->start_date = $data['startDate'];
            $r->end_date = $data['endDate'];
            $r->User = Doctrine::getTable("User")->find($data['user_id']);
            $r->Aircraft = Doctrine::getTable("Aircraft")->find($data['aircraft']);
            $r->status_id = 1;
            $r->save();
            $r->refresh();

            return $r->id;
        }
    }

    /**
     * Check if a reservation is available to be stored
     * @param array $params
     * @return boolean
     */
    public static function isAvailable($params)
    {
        $rv = false;

        if(!empty($params))
        {
            $r = Doctrine_Query::create()
                    ->from("Reservation r")
                    ->andWhere("r.start_date > '{$params['startDate']}' AND r.start_date < '{$params['endDate']}'")
                    ->orWhere("r.end_date > '{$params['startDate']}' AND r.end_date  < '{$params['endDate']}'")
                    ->fetchArray(true);

            if(empty($r))
                return true;

            $isValid = false;
            foreach($r as $reservation)
            {
                if ($reservation['user_id'] == $params['user_id'])
                    return false;

                if($reservation['aircraft_id'] <> $params['aircraft'])
                    $isValid = true;
                else
                    $isValid = false;
            }
            return $isValid;
        }
        return false;
    }

    /**
     * Cancel future reservations
     * @param int $userId
     * @return int count modified rows
     */
    public static function cancelFutureByUserId($userId)
    {
        $now = date("Y-m-d G:i:s", time());
        $count = 0;

        $result = Doctrine_Query::create()
                    ->from("Reservation r")
                    ->addWhere("r.user_id = $userId")
                    ->addWhere("r.start_date > '$now'")
                    ->execute();

        if(!empty($result))
        {
            foreach($result as $reservation)
            {
                $count += 1;
                $reservation->status_id = 2;
                $reservation->save();
            }
        }

        return $count;
    }

    /**
     * Get a reservation and his relations from a reservation_id
     * @param int $id
     * @return array
     */
    public static function findById($id)
    {
        if (empty($id))
            return array();
        
        return Doctrine_Query::create()
                    ->from(__CLASS__ . " r")
                    ->leftJoin("r.ReservationStatus rs")
                    ->leftJoin("r.User u")
                    ->leftJoin("r.Aircraft a")
                    ->where("r.id = $id")
                    ->fetchOne()
                    ->toArray(true);
    }

    /**
     * Cancel a reservation by his id
     * @param int $id reservation id
     * @return int number of rows affected
     */
    public static function cancelById($id)
    {
        if (empty($id))
            return 0;

        return Doctrine_Query::create()
                    ->update(__CLASS__ . " r")
                    ->set("r.status_id", 2)
                    ->where("r.id = $id")
                    ->execute();
    }
}