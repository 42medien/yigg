<?php

/**
 * BaseStoryCategory
 * 
 * @property integer $id
 * @property integer $story_id
 * @property integer $category_id
 * 
 * @method integer  getId()       Returns the current record's "id" value
 * @method integer  getStoryId()  Returns the current record's "story_id" value
 * @method integer  getCategoryId()    Returns the current record's "category_id" value
 * @method StoryTag setId()       Sets the current record's "id" value
 * @method StoryTag setStoryId()  Sets the current record's "story_id" value
 * @method StoryTag setCategoryId()    Sets the current record's "category_id" value
 * 
 * @package    yigg
 * @subpackage model
 * @author     Your name here
 */
abstract class BaseStoryCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('story_category');
        $this->hasColumn('id', 'integer', 11, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('story_id', 'integer', 11, array(
             'notnull' => true,
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('category_id', 'integer', 11, array(
             'notnull' => true,
             'type' => 'integer',
             'length' => 11,
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}