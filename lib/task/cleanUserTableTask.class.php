<?php
class cleanUserTableTask extends yiggTaskToolsTask {

  protected function configure () {
    $this->namespace = 'app';
    $this->name = 'cleanUserTable';
    $this->briefDescription = 'Delete unactivated users';
    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
  }

  function preExecute() { }

  public function executeWork($arguments = array(), $options = array()) {
    Doctrine_Query::create()
      ->delete()
      ->from('User u')
      ->andWhere("u.created_at <= ?", "DATE_SUB(NOW(), INTERVAL 14 DAY)")
      ->andWhere("u.status = 0")
      ->execute();
  }

  function preExit() { }
}