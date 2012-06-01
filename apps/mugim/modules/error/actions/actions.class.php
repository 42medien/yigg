<?php 
class errorActions extends sfActions
{
  public function executeError404(sfWebRequest $request)
  {
  	return sfView::SUCCESS;
  }
}
?>