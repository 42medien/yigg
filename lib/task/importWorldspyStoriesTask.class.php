<?php
/**
 * Imports the best stories from the worldspy into Neueste Nachrichten
 */
class importWorldspyStoriesTask extends yiggTaskToolsTask
{
protected function configure()
  {
    $this->namespace        = 'worldspy';
    $this->name             = 'importWorldspyStories';
    $this->briefDescription = 'Imports the best stories from the worldspy into Neueste Nachrichten';
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
               ->addWhere("(SELECT COUNT(s.id) as cnt FROM Story AS s WHERE s.external_url = fe.long_link AND s.deleted_at IS NULL) = 0" )
               ->execute();

    $this->log(sprintf("Found %s entries", count($entries)));

    $entries_to_create = array();
    foreach($entries as $entry)
    {
      if($entry->qualifiesForYiggStory())
      {
        $entries_to_create[] = $entry;
      }
    }
    $this->log(sprintf("%s Entries qualified", count($entries_to_create)));
    if(count($entries_to_create) > 0)
    {
      $this->createStories($entries_to_create, 57086);
    }
    
    $this->logSection('do', 'test');
    
  }

  private function createStories($entries, $user_id)
  {
    foreach($entries as $entry)
    {
      $entry->createStoryFromEntry($user_id);
    }
  }

  public function preExit()
  {

  }

  public function preExecute()
  {
  }
}
?>