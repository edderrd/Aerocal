<?php

/**
 * BaseAircraftType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property varchar $type
 * @property Aircraft $Aircraft
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAircraftType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('aircraft_type');
        $this->hasColumn('id', 'integer', null, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             ));
        $this->hasColumn('type', 'varchar', 200, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => '200',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Aircraft', array(
             'local' => 'id',
             'foreign' => 'type_id'));
    }
}