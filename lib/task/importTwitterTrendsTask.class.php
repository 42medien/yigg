<?php
class importTwitterTrendsTask extends yiggTaskToolsTask
{
  protected function configure()
  {
    $this->namespace        = 'app';
    $this->debug = true;
    $this->name             = 'importTwitterTrends';
    $this->briefDescription = 'Imports latest german twitter trends';
    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', "frontend");
  }

  public function preExecute()
  {
  }

  public function executeWork($arguments = array(), $options = array())
  {
    $results = simplexml_load_file("http://www.twitter-trends.de/trends.xml");

    foreach($results->latest_trends->trend as $trend)
    {
      if(intval($trend->attributes()->frequency) > 120)
      {
            continue;
      }
      $term = html_entity_decode((string)$trend->attributes()->label, ENT_QUOTES,"UTF-8");
      if(strlen($term) < 3)
      {
          continue;
      }


      if(false === Doctrine::getTable("TwitterSearch")->findOneByTerm($term))
      {
          $search = new TwitterSearch();
          $search->term = $term;
          $search->save();
      }
    }
  }

  public function preExit()
  {
  }
}
