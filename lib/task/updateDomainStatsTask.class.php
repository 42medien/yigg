<?php

class updateDomainStatsTask extends yiggTaskToolsTask
{
  protected function configure ()
  {


    $this->namespace = 'app';
    $this->name = 'updateDomainStats';
    $this->briefDescription = 'Refreshes the domain stats';
    $this->addArgument('application',
    sfCommandArgument::OPTIONAL, 'The application name', "frontend");
  }
  function preExecute ()
  {
  }

  function executeWork ($arguments = array(), $options = array())
  {
    $this->importNewStories();

    $domains = Doctrine_Query::create()
               ->from("Domain")
               ->limit(100)
               ->where("updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY)")
               ->execute();

    foreach($domains as $domain)
    {
    	$domain->updateStats();
      $domain->save();
    }
  }

  function importNewStories()
  {
    $stories = Doctrine_Query::create()
               ->from("Story")
               ->where("id > ?", $this->status->getConfig()->get("latest_story_id", 0))
               ->limit(100)
               ->execute();

    foreach($stories as $story)
    {
    	$hostname = parse_url($story["external_url"], PHP_URL_HOST);
      if(true === empty($hostname))
      {
        continue;
      }
    	$domain = Doctrine::getTable("Domain")->findOneByHostname($hostname);
      $this->log($hostname);
    	if($domain === false)
    	{
    		$domain = new Domain();
    		$domain->hostname = $hostname;
        $domain->updateStats();
        $domain->trySave();
    	}
      if(empty($story->domain_id))
      {
        $story->domain_id = $domain->id;
        $story->save();
      }
    	if(false === empty($story["id"]))
    	{
    	  $this->status->getConfig()->set("latest_story_id", $story["id"]);
    	}
    }
  }

  function preExit ()
  {
  }

  function log($msg)
  {
  	echo $msg;
  }
}
?>