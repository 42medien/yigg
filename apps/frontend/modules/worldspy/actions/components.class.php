<?php
/**
 *
 * @package 	yigg
 * @subpackage 	story
 */
class worldSpyComponents extends sfComponents
{
  /**
   * This is run whenever this component is included
   *
   */
  public function executeNavigation($request)
  {
  }
  /**
   * Executes thesponsorings
   */
  public function executeSponsorings()
  {
  }

  public function executeRecentEntriesForTag()
  {
    $query = Doctrine_Query::create()
                     ->from("FeedEntry")
                     ->where("description LIKE ?", "%$this->tag%")
                     ->orderBy('id DESC')
                     ->limit(3);

    $this->entries = $query->execute();
  }
}