<?php

/**
 * spy actions.
 *
 * @package    yigg
 * @subpackage story
 */
class spyActions extends yiggActions {
  /**
   * Shows the Spy version for yigg
   *
   */
  public function executeSpy( $request ) {
    $this->setLayout("layout.stream.full");
    // Execute the view with the filters.
    $stories = $this->getDbView( $request->getParameter("show", "all") );
    if(!$stories) {
      return sfView::ERROR;
    }

    $this->stories = $stories;
    return sfView::SUCCESS;
  }

  /**
   * Gets the appropriate view syntax for execution.
   * @return sfView
   * @param $filter string the spy mode
   * @param $categories the category filter.
   */
  private function getDbView( $filter , $timestamp = null ) {

    switch( $filter )
    {
      // Show everything
      default:
      case 'all':
        return $this->getLatestEvents();
      break;

      // Only show new stories
      case 'new':
        return $this->getLatestStories();
      break;

      // Only show new ratings
      case 'top-rated':
        return $this->getLatestRatings();
      break;

      // Only show comments
      case 'latest-comments':
        return $this->getLatestComments();
      break;

      // Only show Story renders.
      case 'last-read' :
        return $this->getLatestRenders();
      break;
    }
  }

  private function getLatestStories() {
    $sf = new yiggStoryFinder();
    $sf->sortByDate();
    $sf->confineWithDate24();
    $sf->setLimit(50);
    
    return $sf->getQuery()->execute();
  }

  private function getLatestRatings() {
    $sf = new yiggStoryFinder();
    $sf->sortByRatingDate();
    $sf->setLimit(50);
    
    return $sf->getQuery()->execute();
  }

  private function getLatestComments() {
    $sf = new yiggStoryFinder();
    $sf->sortByLatestComment();
    $sf->setLimit(50);
    
    return $sf->getQuery()->execute();
  }

  private function getLatestRenders() {
    $sf = new yiggStoryFinder();
    $sf->sortByViews();
    $sf->setLimit(50);
    
    return $sf->getQuery()->execute();
  }

  private function getLatestEvents() {
    $sf = new yiggStoryFinder();
    $sf->sortByDate();
    $sf->setLimit(50);
    
    return $sf->getQuery()->execute();
  }
}