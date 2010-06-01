<?php

/**
 * BaseAircraft
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $type_id
 * @property varchar $name
 * @property integer $status_id
 * @property AircraftType $AircraftType
 * @property AircraftStatus $AircraftStatus
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAircraft extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('aircraft');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'autoincrement' => true,
             'primary' => true,
             ));
        $this->hasColumn('type_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('name', 'varchar', 300, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => '300',
             ));
        $this->hasColumn('status_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('AircraftType', array(
             'local' => 'type_id',
             'foreign' => 'id'));

        $this->hasOne('AircraftStatus', array(
             'local' => 'status_id',
             'foreign' => 'id'));
    }
}