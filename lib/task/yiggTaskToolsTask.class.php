<?php
abstract class yiggTaskToolsTask extends sfBaseTask
{
  public $status;
  public $debug = false;

  /**
   * Common actions that have to be executed in every task
   */
  private function setup()
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
    sfContext::createInstance($configuration);
    $databaseManager = new sfDatabaseManager($this->configuration);
    $this->status = TaskStatus::getTaskStatus($this);

    $this->logger = new sfFileLogger(
      sfContext::getInstance()->getEventDispatcher(),
      array('file' => './log/tasks/'.get_class($this).'.log'));

    if(false === $this->status)
    {
      $this->log("Status row in Database does not exist");
      $this->status = new TaskStatus();
      $this->status->task_name = get_class($this);
      $this->status->save();
    }

    $this->status->description = substr($this->briefDescription, 0, 120);

    $this->log("Task was executed via cronjob.");
  }

  function execute($arguments = array(), $options = array())
  {
    $this->setup();

    if($this->isLocked() && $this->debug !== true)
    {
      $this->log("Skipping because of lock");
      exit();
    }
    
    $this->setLock(true);

    try
    {
      $this->preExecute();
    $this->executeWork($arguments, $options);
      $this->preExit();
    }
    catch(Exception $e)
    {
      $this->log($e->getMessage());
    }

    $this->status->last_run = yiggTools::getDbDate();
    $this->setLock(false);
  }

  /**
   * Sets the lock to true or false
   */
  private function setLock($status)
  {
    $this->status->is_locked = $status;
    $this->status->save();
  }

  /**
   * Checks if this task is locked
   * @return boolean
   */
  private function isLocked()
  {
    return true === $this->status->is_locked;
  }

  /**
   * Exists the task on high load average
   */
  function exitOnHighLoadAverage()
  {
    $load_avg = yiggSystemTools::getLoadAvergage();
    if($load_avg[1] > sfConfig::get("app_highloadMeasures_highLoad", 8))
    {
        $this->preExit();
        $this->setLock(false);
        exit();
    }
  }

  /**
   * Actions required to take down the Task
   * You can not use the desturctor because it will interfear with ./symfony commandline command
   */
   abstract function preExit();

   /**
    * Functions that does the work in the inherited task
    */
  abstract function executeWork($arguments = array(), $options = array());

  /**
   * Logs a string and appends classname, and writes it into the propper directory
   * @param String $msg
   */
  public function log($msg)
  {
    $msg = sprintf("{%s} - %s", get_class($this), $msg);
    $this->logger->log($msg);
  }
}
?>
