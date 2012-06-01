<?php

/**
 *
 * @package   yigg
 * @subpackage   story
 */
class userComponents extends sfComponents
{
  /**
   * This is run whenever this component is included
   *
   */
  public function executeNavigation( $request )
  {
    $this->current = $request->getAction();
  }

  public function executeLoginform($request)
  {
    $this->form = new FormUserLogin();
  }


  public function executeTopUsersOffTheDay($request)
  {
      $query = Doctrine_Query::create()
               ->from('User u')
               ->where('u.last_login > DATE_SUB(NOW(), INTERVAL 1 DAY)')
               ->addWhere('u.avatar_id IS NOT NULL')
               ->addWhere('privacy != 1')
               ->orderBy("(SELECT COUNT(*) FROM UserOnlineLog ul WHERE ul.user_id = u.id AND ul.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)) DESC")
               ->limit(10);

      $this->users = $query->execute();
      return sfView::SUCCESS;
  }
}
