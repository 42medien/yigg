<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class Node extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('story');
    $this->hasColumn('epoch_time', 'float', array ());
    $this->hasColumn('story_reference_id', 'integer', 4, array ());
    $this->hasColumn('created_at', 'timestamp', 25, array ());
    $this->hasColumn('user_id', 'integer', 4, array ());
    $this->hasColumn('story_title', 'string', 128, array ());
    $this->hasColumn('story_external_url', 'string', 128, array ());
    $this->hasColumn('story_internal_url', 'string', 128, array ());
    $this->hasColumn('story_created_at', 'timestamp', 25, array ());
    $this->hasColumn('story_user_id', 'integer', 4, array ());
    $this->hasColumn('currentRating', 'integer', 4, array());
    $this->hasColumn('username', 'string', 32, array ());
    $this->hasColumn('tablename', 'string', 32, array ());
    $this->option('collate', 'utf8_unicode_ci');
    $this->option('charset', 'utf8');
  }
}