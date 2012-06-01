<?php
class RedirectTable extends Doctrine_Table
{
	public static function getTable()
	{
      return Doctrine::getTable('Redirect');
	}
	
	public static function countAll()
	{
      $query = new Doctrine_Query();
	  $query ->select("*")
	         ->from("Redirect");
	  return $query->count();
	}

	public static function getByUrl($url)
	{
	  $query = new Doctrine_Query();
	  $query ->select("*")
	         ->from("Redirect")
	         ->where("url = ?", $url);
	  return $query->fetchOne();
	}
}
?>