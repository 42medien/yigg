<?php

/**
 *
 * @package     yigg
 * @subpackage  story
 */
class CategoryTable extends Doctrine_Table {
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable() {
    return Doctrine::getTable('Category');
  }

  public function getCategories() {
    $q = Doctrine_Query::create()->from('Category')->execute();

    return $q;
  }
}