<?php
class VideoAdForm extends BaseVideoAdForm
{

  public function configure()
  {
    $this->removeFields();
    parent::configure();
  }

  protected function removeFields()
  {
    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['internal_url'],
      $this['deleted_at'],
      $this['ratings_list']
    );
  }
}
