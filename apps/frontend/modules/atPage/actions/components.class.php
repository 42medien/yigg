<?php

/**
 *
 * @package   yigg
 * @subpackage   story
 */
class atPageComponents extends sfComponents
{

  /**
   * This is run whenever this component is included
   *
   */
  public function executeNavigation( $request )
  {
    $this->current = $request->getAction();
    $this->view    = $request->getParameter("view");
  }

  public function executePmFormForUser($request)
  {
    $this->form = new FormPostboxSimpleCreate();
    $this->form->setDefault("message","@" . $this->user->username);
  }
}

?>