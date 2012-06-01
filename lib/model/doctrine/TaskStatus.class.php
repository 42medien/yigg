<?php
class TaskStatus extends BaseTaskStatus
{
  public static function getTaskStatus($task)
  {
  	$query = new Doctrine_Query();
	  $query ->select("*")
	       ->from("TaskStatus")
		   ->where("task_name = ?", get_class($task));
	  return $query->fetchOne();
  }
}