<?php
/**
 * Imports the best stories from the worldspy into Neueste Nachrichten
 */
class updateSocialMediaNewsroom extends yiggTaskToolsTask
{
protected function configure()
  {
    $this->namespace        = 'worldspy';
    $this->name             = 'updateNewsroom';
    $this->briefDescription = 'Marks good stories for social media newsroom';
    $this->addArgument('application', sfCommandArgument::REQUIRED, 'The application name');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
  }

  /**
   * Setup the task and execute it.
   */
  public function executeWork($arguments = array(), $options = array())
  {
    $entries = Doctrine_Query::create()
               ->select('*')
               ->from('FeedEntry fe')
               ->where('created > DATE_SUB(NOW(), INTERVAL 3 HOUR)')
               ->addWhere('in_newsroom IS NULL')
               ->addWhere("(SELECT COUNT(s.id) as cnt FROM Story AS s WHERE s.external_url = fe.long_link AND s.deleted_at IS NULL) = 0" )
               ->execute();


    $cnt = 0;
    foreach($entries as $entry)
    {
      if($entry->qualifiesForNewsroom())
      {
        $cnt++;
        $entry->in_newsroom = true;
        $entry->save();
      }
    }
    $this->log(sprintf("%s new entries in newsroom", $cnt));
  }

  public function preExit()
  {

  }

  public function preExecute()
  {
  }
}
?>