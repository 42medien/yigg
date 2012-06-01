<?php

/**
 *
 * @package     yigg
 * @subpackage  user
  */
class RememberKeyTable extends Doctrine_Table
{

  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('RememberKey');
  }
}