<?php
class errorActions extends sfActions {
  public function executeError404(sfWebRequest $request) {
  	$this->redirect("http://yigg.de/system/error404");
  }
}