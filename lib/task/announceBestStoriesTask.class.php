<?php
class announceBestStoriesTask extends yiggTaskToolsTask
{
  protected function configure ()
  {
    $this->debug = false;
    $this->namespace = 'app';
    $this->name = 'announceBestStories';
    $this->briefDescription = 'Announces the best stories';
    $this->addArgument('application',
    sfCommandArgument::OPTIONAL, 'The application name', "frontend");
  }
  function preExecute ()
  {
  }

  function executeWork ($arguments = array(), $options = array())
  {
    $this->findGlobalBestStories();
    $users = Doctrine_Query::create()
            ->from("User")
            ->where("last_login > DATE_SUB(NOW(), INTERVAL 1 WEEK)")
            ->orderBy("last_login DESC")
            ->execute();

    foreach($users as $user)
    {
      $this->findPersonalBestStories($user);
    }
  }

  public function findPersonalBestStories($user)
  {
    $sf = new yiggStoryFinder();
    $sf->confineWithDate24( true );
    $sf->sortByRating()->sortByDate();
    $sf->setLimit(50);

    $query = $sf->getQuery();
    $query->addWhere("s.user_id != 626");

    $query->leftJoin('story_tag AS st ON s.id = st.story_id')
          ->addWhere("st.tag_id IN (SELECT ut.tag_id FROM user_tag AS ut WHERE ut.user_id = ?)
                OR s.user_id IN (SELECT uf.following_id FROM user_following AS uf WHERE uf.user_id = ?)
                OR s.domain_id IN (SELECT ud.domain_id FROM user_domain_subscription AS ud WHERE ud.user_id = ?)",
              array($user->id, $user->id, $user->id));


    $stories = $query->execute();
    $this->log("Found {$stories->count()} nice stories for user{$user->username}");
    $this->markStories($stories, $user->id);
  }

  public function findGlobalBestStories()
  {
    $sf = new yiggStoryFinder();
    $sf->confineWithDate24( true );
    $sf->sortByAvg()->sortByDate();
    $sf->setLimit(25);
    $stories = $sf->executeQuery();
    $this->markStories($stories);
  }

  private function markStories($stories, $user_id=null)
  {
    foreach($stories as $story)
    {
      $query = Doctrine::getTable("History")->getQueryObject()
               ->where("story_id = ?", $story->id);

      if(false === empty($user_id))
      {
        $query->addWhere("user_id = ?", $user_id);
      }
      else
      {
        $query->addWhere("user_id IS NULL");
      }

      $history = $query->fetchOne();
      
      if(false !== $history)
      {
        continue;
      }
      $history = new History();

      $history->fromArray(
        array(
          "story_id" => $story->id,
          "user_id" => $user_id,
          "created_at" => yiggTools::getDbDate()
        )
      );
      $history->save();
    }
  }

  public function log($msg)
  {
    parent::log($msg);
  }


  function preExit ()
  {
  }

}
?>