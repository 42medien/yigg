<?php
class Category extends BaseCategory {
  public function getCategorySlug() {
    // replace all non letters or digits by -
    $text = preg_replace('/\W+/', '-', $this->getName());

    // trim and lowercase
    $text = strtolower(trim($text, '-'));

    return $text;
  }

  public function getStories($limit = 10) {
    $sf = new yiggStoryFinder();
    $sf->confineWithCategory($this->getId());
    $sf->sortByDate();
    $sf->setLimit($limit);
    return $sf->executeQuery();
  }

  public function countStories() {
    $query = Doctrine_Query::create()
      ->select("sc.id")
      ->from("StoryCategory sc")
      ->where("sc.category_id = " . $this->getId());
    return $query->count();
  }
}