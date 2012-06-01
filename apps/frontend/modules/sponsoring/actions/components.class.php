<?php

/**
 *
 * @package   yigg
 * @subpackage   sponsoring
 */
class sponsoringComponents extends sfComponents
{
  /**
   * This is run whenever this component is included
   *
   */
  public function executeNavigation()
  {
  }

  /**
   * Runs the component for adding a sponsorship placement on a place.
   * Includes code for previewing a sponsorship before ordering.
   *
   * @param yiggWebRequest $request
   */
  public function executeSponsoring($request)
  {
    if( !isset($this->place_id) || $this->place_id === null || $this->place_id === false)
    {
      return sfView::NONE;
    }

    if(true === $request->hasParameter("sponsoring_place")  && ((int) $request->getParameter("sponsoring_place") == $this->place_id) )
    {
      if( $request->hasParameter('sponsoring_url') )
      {
        $url = htmlentities( strip_tags( trim( (string) $request->getParameter('sponsoring_url'))), ENT_NOQUOTES, 'UTF-8');
        if( true === yiggTools::isProperURL( $url ))
        {
          $this->preview_url = $url;
        }
      }
      if($request->hasParameter('sponsoring_image'))
      {
        $file = Doctrine::getTable('File')->findOneById( (int) $request->getParameter('sponsoring_image') );
        if( $file !== false && true === $file->hasFile() )
        {
          $this->preview_image = $file;
        }
      }
    }

    //assign sponsorship information.
    $this->place_sponsors = SponsoringTable::getSponsoringsForPlace( $this->place_id );

    // @todo this should probably be an association....
    // caching
    $this->place = Doctrine::getTable('SponsoringPlace')->findOneById( $this->place_id );
    if( false !== $this->place )
    {
      $this->place->setPlacesCount(count($this->place_sponsors));
    }

    // lookup failed.
    if(false === $this->place)
    {
      return sfView::NONE;
    }
  }
}