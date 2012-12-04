<?php

/**
 *
 * @package   yigg
 * @subpackage   story
 */
class systemComponents extends sfComponents
{
  /**
   * Render Feeds
   */
  public function executeFeeds($request)
  {
    if($request->hasParameter("rss"))
    {
      $this->feed = $request->forceParams( array("sf_format" => "atom"));
      return sfView::SUCCESS;
    }
    return sfView::NONE;
  }

  /**
   * Show sponsoring boxes
   */
  public function executeSponsorings(sfWebRequest $request)
  {
    $this->place = $this->findPlace($request);
  }

  /**
   * Render Vibrant Media intelli txt
   */
//  public function executeVibrantAds($request)
//  {
//    $this->intelliId = 11061;
//    return sfView::SUCCESS;
//  }
}
