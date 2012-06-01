<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class Comment extends BaseComment
{
  public function getParentObj()
  {
    if($this->hasRelation("Stories") && $this->Stories->count() > 0)
    {
      return $this->Stories->getFirst();
    }
  }

  /**
   * Returns boolean if the usersession is the author of the comment.
   *
   * @param yiggSession $userSession
   * @return Boolean
   */
  public function isAuthor( $userSession )
  {
    if( true === $userSession->hasUser() )
    {
      return $this->user_id === $userSession->getUserId();
    }
    return false;
  } 

  /**
   * Sends Pms and emails after the creation of the comment.
   */
  public function processNotifications($associate)
  {
    if($this->is_online === false)
    {
      return;
    }

    $exclude_users = array();
    $request = sfContext::getInstance()->getRequest();
    $conn = Doctrine::getConnectionByTableName("Comment");
    //notify Author.

    $parent = $associate->getParent();
    if( $parent->user_id !== $this->user_id )
    {
      $parent->Author->notify(
        $associate,
        "AuthorComment",
        true,
        $conn
      );
      $exclude_users[$parent->user_id] = true;
    }

    // Send @-notifications
    $users = yiggTools::getAdressedUsers($this->description);
    if( count($users) > 0 )
    {
      foreach( $users as $user )
      {
        if( $this->user_id !== $user->id && false === array_key_exists( $user->id, $exclude_users) )
        {
          $user->notify(
            $associate,
            "AtMention",
            true,
            $conn
          );
          $exclude_users[$user->id] = true;
        }
      }
    }

    // Process newer comments.
    $exclude_users[$this->user_id] = true;
    if(count($parent->Comments) > 0)
    {
      foreach($parent->Comments as $comment)
      {
        if(false === array_key_exists($comment->user_id, $exclude_users) )
        {
          $comment->Author->notify(
            $associate,
            "NewerComment",
            true,
            $conn
          );
          $exclude_users[ (int) $comment->user_id] = true;
        }
      }
    }
  }

  /**
   * Deletes reference between comment and parent in the appropriate table.
   * @return unknown_type
   */
  public function preDelete($event)
  {
    if(false === is_null($this->getParentObj())) # If parent object was deleted do nothing
    {
      Doctrine::getTable(get_class($this->getParentObj())."Comment")->findOneByCommentId($this->id)->deleteAssociatedNotifications();
    }
    parent::preDelete($event);
  }

  public function preSave($event)
  {
    if(false === empty($this->id))
    {
      return;
    }
    $links = yiggTools::findUrlsInString($this->description);
    if(empty($links))
    {
      return;
    }
    foreach($links as $url)
    {
      $link = new CommentLink();
      $link->fromArray(
        array(
          "comment_id" => $this->id,
          "url" => $url
      ));
      $this->Link = $link;
    }
  }

  /**
   * Check if its possible to delete a comment
   *
   * @param  $user
   * @return bool
   */
  public function hasDeletePermissions(User $user)
  {
    return $user->id === $this->user_id || $user->isAdmin();
  }

  public function getTweetLink($object)
  {
    return "@comment_actions?action=tweet&object=" . strtolower(get_class($object))."&id=".$object['id']."&ref_id=".$this['id'];
  }

  public function getEditLink($object)
  {
    return "@comment_actions?action=edit&object=" . strtolower(get_class($object))."&id=".$this['id'];
  }

  public function getDeleteLink()
  {
    return "@comment_actions?action=delete&object=comment&id=".$this['id'];
  }
}