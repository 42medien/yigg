<?php
class searchForm extends yiggForm
{
  public function setup()
  {
    parent::setup();

    unset($this["_csrf_token"]);
    $this->setWidget('q', new sfWidgetFormInput(array(), array("type" => "search", "placeholder" => "Suche", "class" => "search")));
    $this->setValidator('q',new sfValidatorString(array('max_length'  => 128)));

    $this->widgetSchema->setLabel('q', 'Suche');
    $this->getWidgetSchema()->setNameFormat("%s");
  }
}