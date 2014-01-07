<?php

/**
 *
 * @package     yigg
 * @subpackage  story
 */
class StoryFilterOptionTable extends Doctrine_Table
{
    /**
     * return instance of current table
     *
     * @return Doctrine_Table
     */
    public static function getTable()
    {
        return Doctrine::getTable('StoryFilterOption');
    }

    public function getStoryFilterOptions()
    {
        $q = Doctrine_Query::create()
                ->from('StoryFilterOption')->execute();

        return $q;
    }
}