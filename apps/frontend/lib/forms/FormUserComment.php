<?php
class FormUserComment extends FormComment
{

  public function setup()
  {
    parent::setup();
    unset(
      $this["email"],
      $this["name"]
    );
  }
}