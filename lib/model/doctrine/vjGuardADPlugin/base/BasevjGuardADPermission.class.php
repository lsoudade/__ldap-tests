<?php

/**
 * BasevjGuardADPermission
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $description
 * @property Doctrine_Collection $Groups
 * @property Doctrine_Collection $Users
 * @property Doctrine_Collection $vjGuardADGroupPermission
 * @property Doctrine_Collection $vjGuardADUserPermission
 * 
 * @method string              getName()                     Returns the current record's "name" value
 * @method string              getDescription()              Returns the current record's "description" value
 * @method Doctrine_Collection getGroups()                   Returns the current record's "Groups" collection
 * @method Doctrine_Collection getUsers()                    Returns the current record's "Users" collection
 * @method Doctrine_Collection getVjGuardADGroupPermission() Returns the current record's "vjGuardADGroupPermission" collection
 * @method Doctrine_Collection getVjGuardADUserPermission()  Returns the current record's "vjGuardADUserPermission" collection
 * @method vjGuardADPermission setName()                     Sets the current record's "name" value
 * @method vjGuardADPermission setDescription()              Sets the current record's "description" value
 * @method vjGuardADPermission setGroups()                   Sets the current record's "Groups" collection
 * @method vjGuardADPermission setUsers()                    Sets the current record's "Users" collection
 * @method vjGuardADPermission setVjGuardADGroupPermission() Sets the current record's "vjGuardADGroupPermission" collection
 * @method vjGuardADPermission setVjGuardADUserPermission()  Sets the current record's "vjGuardADUserPermission" collection
 * 
 * @package    GL-Events-tests
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasevjGuardADPermission extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('vj_guard_a_d_permission');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('vjGuardADGroup as Groups', array(
             'refClass' => 'vjGuardADGroupPermission',
             'local' => 'permission_id',
             'foreign' => 'group_id'));

        $this->hasMany('vjGuardADUser as Users', array(
             'refClass' => 'vjGuardADUserPermission',
             'local' => 'permission_id',
             'foreign' => 'user_id'));

        $this->hasMany('vjGuardADGroupPermission', array(
             'local' => 'id',
             'foreign' => 'permission_id'));

        $this->hasMany('vjGuardADUserPermission', array(
             'local' => 'id',
             'foreign' => 'permission_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $this->actAs($timestampable0);
    }
}