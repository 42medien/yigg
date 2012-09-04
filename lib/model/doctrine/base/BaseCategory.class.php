<?php

/**
 * BaseCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $Users
 * @property Doctrine_Collection $Stories
 * 
 * @method integer             getId()      Returns the current record's "id" value
 * @method string              getName()    Returns the current record's "name" value
 * @method Doctrine_Collection getStories() Returns the current record's "Stories" collection
 * @method Tag                 setId()      Sets the current record's "id" value
 * @method Tag                 setName()    Sets the current record's "name" value
 * @method Tag                 setStories() Sets the current record's "Stories" collection
 * 
 * @package    yigg
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('category');
        $this->hasColumn('id', 'integer', 11, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('name', 'string', 128, array(
             'notnull' => true,
             'type' => 'string',
             'unique' => true,
             'length' => 128,
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Story as Stories', array(
             'refClass' => 'StoryCategory',
             'local' => 'category_id',
             'foreign' => 'story_id'));

        $yiggmultiplereference0 = new yiggMultipleReference(array(
             ));
        $this->actAs($yiggmultiplereference0);
    }
}