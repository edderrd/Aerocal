<?php

/**
 * BaseReservation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property datetime $start_date
 * @property datetime $end_date
 * @property integer $user_id
 * @property integer $aircraft_id
 * @property User $User
 * @property Aircraft $Aircraft
 * @property Doctrine_Collection $ReservationStatus
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReservation extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('reservation');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('start_date', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('end_date', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('aircraft_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Aircraft', array(
             'local' => 'aircraft_id',
             'foreign' => 'id'));

        $this->hasMany('ReservationStatus', array(
             'local' => 'id',
             'foreign' => 'reservation_id'));
    }
}