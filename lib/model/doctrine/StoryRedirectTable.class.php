<?php
class StoryRedirectTable extends Doctrine_Table
{
	public static function getTable()
	{
      return Doctrine::getTable('StoryRedirect');
	}
}