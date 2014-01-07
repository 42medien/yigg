<?php
class tagActions extends yiggActions {
  public function executeIndex() {
    $tags = Doctrine::getTable("Tag")->getTagsByCount();

    $first = reset($tags);
    $last = end($tags);

  	$this->min = $last["tag_count"];
    $this->max = $first["tag_count"];

    shuffle($tags);

    $this->tags = $tags;
  }

  public function executeChangeSubscription($request) {
    $tag = Doctrine::getTable("Tag")->findOneById($request->getParameter("tag_id"));
    $this->forward404Unless($tag, "Keine passenden Tags gefunden");

    $user = $this->session->getUser();

    if($user->followsTag($tag))
    {
      Doctrine_Query::create()
      ->from("UserTag")
      ->where("tag_id = ?", $tag->id)
      ->addWhere("user_id = ?", $user->id)
      ->fetchOne()
      ->delete();
      $this->session->setFlash("msg_success", "Du hast nun den Tag {$tag->name} abonniert");
    }
    else
    {
      $user_tag = new UserTag();
      $user_tag->user_id = $user->id;
      $user_tag->tag_id = $tag->id;
      $user_tag->save();
      $this->session->setFlash("msg_success", "Du hast nun den Tag {$tag->name} abonniert");
    }
    return $this->redirectBack();
  }

  public function executeShow($request) {
    $this->setLayout("layout.stream");
    $tag = $request->getParameter("tags");

    $this->tag = Doctrine::getTable("Tag")->findOneByName($tag);
    $this->forward404Unless($this->tag, "Keine passenden Tags gefunden");

    $story_finder = new yiggStoryFinder();
    $story_finder->confineWithTags($this->tag);

    switch($request->getParameter("sort"))
    {
        default: case "date":
            $story_finder->sortByDate();
            break;
        case "beste":
            $this->getResponse()->addMeta('index', 'noindex/follow');
            $story_finder->confineWithMarkedForFrontpage();
            $story_finder->sortByDate();
            break;
    }

    $video_finder = clone($story_finder);

    $base_url = sfContext::getInstance()->getController()->genUrl('@tag_show');

    $this->limit = 10;
    $this->stories = $this->setPagerQuery(
        $story_finder->getQuery(),
       "$base_url?sort={$request->getParameter("sort")}&tags={$this->tag->name}"
    )->execute();

    if(0 === count($this->stories))
    {
      return $this->forward404();
    }

    $this->getResponse()->setTitle( sprintf('Alles zum Tag %s', $this->tag->name ));
    $this->getResponse()->addMeta('keywords', $this->tag->name );
    $this->getResponse()->addMeta('description', sprintf('Auf dieser Seite findest du alles auf YiGG zum Schlagwort %s.',$this->tag_params));

    return sfView::SUCCESS;
  }
}