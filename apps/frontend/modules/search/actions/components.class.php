<?php
class searchComponents extends sfComponents
{
  public function executeForm($request)
  {
    $this->form = new searchForm();
    $this->form->setDefault("q", $request->getParameter("q", null));
  }
}