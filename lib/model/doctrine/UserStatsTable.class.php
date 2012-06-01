<?php
class UserStatsTable extends Doctrine_Table
{
  /**
   * Get user that stats where not calculated during the last 24 hours
   */
    public static function getUsersToCalculate($limit)
    {
      $query = Doctrine_Query::create();
      $query  ->select('us.*')
              ->from('UserStats us')
              ->leftJoin("us.User u")
              ->where('us.last_calculated < ? OR us.last_calculated IS NULL', yiggTools::getDbDate('Y-m-d H:i:s',time()-3600))
              ->addWhere('us.user_id != 1')
              ->addWhere('u.last_login > ?', yiggTools::getDbDate('Y-m-d H:i:s',time()-2592000))
              ->orderBy("u.last_login DESC")
              ->limit($limit);
       return $query->execute();
    }
}