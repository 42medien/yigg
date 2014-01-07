<?php
class RedirectLogTable extends Doctrine_Table
{
  public static function countAll()
  {
    $query = new Doctrine_Query();
	$query ->select("*")
	       ->from("RedirectLog");
	return $query->count();
  }
	
  public static function countRedirectHits($key)
  { 	
  	$query = new Doctrine_Query();  	
  	$query ->select("rl.*")
  	       ->from("RedirectLog rl")
  	       ->where("rl.redirect_id = ?", $key->id);
  	return $query->count();
  }
  
  public static function getHourlyStatistics($key)
  {
  	$query = new Doctrine_Query();
  	$query ->select("COUNT(rl.id) as hits, hour(rl.created_at) as hour")
  	       ->from("RedirectLog rl")
  	       ->where("rl.redirect_id = ?", $key->id)
  	       ->addWhere("created_at > NOW()-86400")
  	       ->groupBy("hour(created_at)")
  	       ->orderBy("created_at");
  	return $query->execute();
  }
}
?>