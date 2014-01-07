<?php
class pagesActions extends sfActions {
  public function executeIndex(sfWebRequest $request) {
  	$this->redirect("http://yigg.de/");
  }
}
