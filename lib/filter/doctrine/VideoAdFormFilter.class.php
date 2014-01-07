<?php

/**
 * VideoAd filter form.
 *
 * @package    yigg
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VideoAdFormFilter extends BaseVideoAdFormFilter
{
  public function configure()
  {
    $this->removeFields();
  }

  protected function removeFields()
  {
    unset(
      $this['updated_at'],
      $this['deleted_at'],
      $this['ratings_list']
    );
  }
}
