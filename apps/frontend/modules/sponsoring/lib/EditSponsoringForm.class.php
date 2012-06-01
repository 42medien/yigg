<?php
class EditSponsoringForm extends FormSponsoring
{
  protected $name = "EditSponsoringForm";

  public function setup()
  {
    parent::setup();
    unset(
      $this["place_id"],
      $this["weeks"],
      $this["invitation_id"],
      $this["image_id"]
    );
    $this->widgetSchema->setNameFormat('EditSponsoringForm[%s]');

  }
}