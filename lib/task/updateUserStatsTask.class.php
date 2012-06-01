<?php
class updateUserStatsTask extends yiggTaskToolsTask
{
  protected function configure()
  {
    $this->namespace        = 'app';
    $this->name             = 'updateUserStats';
    $this->briefDescription = 'Update Statistics for Users';
    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'cli');
  }

  public function preExecute()
  {
  }

  public function preExit()
  {
    $this->log("Stopping UserStatsTask. This is the last line.");
  }

  public function executeWork($arguments = array(), $options = array())
  {
    $no_rows = Doctrine_Query::create()
               ->from("User u")
               ->where("u.id NOT IN (SELECT us.user_id FROM UserStats us)")
               ->fetchArray();

    foreach($no_rows as $row)
    {
      $stat = new UserStats();
      $stat->user_id = $row["id"];
      $stat->save();
    }

    $users = UserStatsTable::getUsersToCalculate(500);

    foreach($users as $user)
    {
      $user->updateStats();
      $this->log(sprintf("Updated User #%s \n", $user->user_id));
    }
  }
}