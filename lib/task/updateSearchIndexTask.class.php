<?php
class updateSearchIndexTask extends yiggTaskToolsTask
{
  const VOTE_THRESHOLD = 3;

  protected function configure ()
  {
    $this->namespace = 'app';
    $this->name = 'updateSearch';
    $this->briefDescription = 'Updates the search index';
    $this->addArgument('application',
    sfCommandArgument::OPTIONAL, 'The application name', "frontend");
  }
  function preExecute ()
  {
  }

  function executeWork ($arguments = array(), $options = array())
  {



    $max_id = StoryIndexTable::getInstance()->getQueryObject()
              ->select('MAX(id)')
              ->fetchOne();

    $max_id = $max_id["MAX"];

    $new_stories = Doctrine_Query::create()
             ->from("Story s")
             ->where("created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)")
             ->addWhere("id > $max_id")
             ->orderBy("id ASC")
             ->fetchArray();

    foreach($new_stories as $story)
    {
      $index = new StoryIndex();
      $index->fromArray($story);
      try{
        $index->save();
      }catch(Exception $e)
      {
        $this->log($e->getMessage());
      }
    }    
  }

  public function log($msg)
  {
    echo $msg;
    parent::log($msg);
  }


  function preExit ()
  {
  }

}
?>