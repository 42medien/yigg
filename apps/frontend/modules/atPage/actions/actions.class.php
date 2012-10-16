<?php
class atPageActions extends yiggActions
{

  /**
   * shows the pinnwand for this user.
   * @param $request
   * @return unknown_type
   */
  public function executeIndex($request)
  {
    $this->form = new FormPostboxSimpleCreate();
    
    if(false !== $request->getParameter("id",false))
    {
      $this->reply_pm = Doctrine::getTable("NotificationMessage")->findOneById(intval($request->getParameter("id")));
      if(false !== $this->reply_pm)
      {
        $this->form->setDefault("message", "@".$this->reply_pm->Sender->username);
      }
      else
      {
        $this->user = Doctrine::getTable("User")->findOneByUsername(
          yiggTools::stringToUsername( (int) $request->getParameter("id") )
        );
        if(false !== $this->user)
        {
          $this->form->setDefault("message", "@".$this->user->username." </p>");
        }
      }

      if( true === $request->isAjaxRequest() && true === $request->isMethod('post'))
      {
        return sfView::SUCCESS;
      }
    }    

    // Support for Pms.
    if(true === $this->form->processAndValidate())
    {
      $conn = Doctrine::getConnectionByTableName("NotificationMessage");
      $conn->beginTransaction();

          $this->recipients = yiggTools::getAdressedUsers($this->form->getValue("message"));
          foreach($this->recipients as $recipient)
          {
              $this->notification = new NotificationMessage();
              $this->notification->sender_id = $this->session->getUserId();
              $this->notification->recipient_id = $recipient->id;
              $this->notification->ref_object_type = "NotificationMessage";
              $this->notification->description = $this->form->getValue("message");
              $this->notification->ref_object_id = 0;
              $this->notification->type = "web";

              $this->notification->save($conn);
          }

      $conn->commit();
      $this->form = new FormPostboxSimpleCreate();

      $this->ajax_pm = "Nachricht gesendet!";
    }
    else
    {
      $this->ajax_pm = "Bug with:" . $this->form->processAndValidate();  
    }

    if(true === $request->isAjaxRequest())
    {
      return sfView::SUCCESS;
    }
    
    $this->limit = 8;
    
    $query = Doctrine::getTable("NotificationMessage")->createQuery()
            ->addWhere("recipient_id = ?", $this->session->getUserId())
            ->addWhere("status != ?", "send")
            ->orderBy("created_at DESC");

    $this->notifications = $this->setPagerQuery($query)->execute();

    Doctrine::getTable("NotificationMessage")->markAsRead($this->notifications);
    
    return sfView::SUCCESS;
  }

  /**
   * shows the notifications for this user.
   * @param $request
   * @return sfView
   */
  public function executeNotificationShow($request)
  {
    $this->view = $request->getParameter("view",'all');
    $query =  Doctrine::getTable("NotificationMessage")->getQueryObject();
    $query->addWhere("status != ?", array("sent"));

    switch($this->view)
    {
      default:
      case "all":
        $query
          ->addwhere("recipient_id = ?", $this->session->getUserId() )
          ->addWhere("ref_object_type <> 'NotificationMessage'");
        break;
        
      case "replies":
          $query
            ->addwhere("recipient_id = ?", $this->session->getUserId() )
            ->addWhere("ref_object_type <> 'NotificationMessage'");
        break;

      case "inbox":
          $query
            ->addWhere("ref_object_type = 'NotificationMessage'")
            ->addwhere("recipient_id = ?", $this->session->getUserId() );
        break;

      case "outbox":
          $query
            ->addWhere("ref_object_type = 'NotificationMessage'")
            ->addwhere("sender_id = ?", $this->session->getUserId() );
        break;
       break;
    }

    $query->orderBy("created_at DESC");
    $this->limit = 8;

    $this->notifications = $this->setPagerQuery($query)->execute();

    Doctrine::getTable("NotificationMessage")->markAsRead($this->notifications);

    $this->form = new FormPostboxSimpleCreate();
    $this->setTemplate("index");
    return sfView::SUCCESS;
  }

  /**
   * Performs a task on a notification
   * @param $request
   * @return unknown_type
   */
  public function executeNotificationTask( $request )
  {
    $notification =  Doctrine::getTable("NotificationMessage")->findOneById( $request->getParameter("id") );
    if(false === $notification->isRecipient($this->session->getUser()) || false === $notification)
    {
      return sfView::ERROR;
    };

    switch( $request->getParameter("task"))
    {
      case "delete":
        $notification->status = "send";
        $notification->save();

        if( true === $this->isAjaxRequest() )
        {
          return sfView::NONE;
        }
        break;
      default:
        return sfView::Error;
      break;
    }

    return $this->redirectBack();
  }

  /**
   * allows pm's to be deleted.
   * @param $request
   * @return unknown_type
   */
  public function executePostboxTask($request)
  {
    $privateMessage =  Doctrine::getTable("PostboxMessage")->findOneById( (int) $request->getParameter("id") );
    switch($request->getParameter("task"))
    {
      case "delete":
        if( false === $privateMessage->isRelatedUser($this->session->getUser()))
        {
          return sfView::Error;
        }
        $privateMessage->deleteFromMailbox($this->session->getUser());
        if($this->isAjaxRequest())
        {
          return sfView::NONE;
        }
      break;
      case "pm":
      case "reply":
        $this->forward("atPage","index");
      break;
    }

    if( true === $this->isAjaxRequest() )
    {
      return $this->renderPartial("pmNode", array("pm" => $privateMessage));
    }
    return $this->redirectBack();
  }


  /**
   * Clears the inbox or outbox for a user based on their parameter
   * @param $request
   * @return unknown_type
   */
  public function executeDeletePostbox($request)
  {
    switch($request->getParameter("postbox"))
    {
      case "inbox":
        Doctrine::getTable("PostboxMessage")->deleteInbox($this->session->getUserId());
        $this->redirect("@postbox_views?view=inbox");
        break;
      case "outbox":
        Doctrine::getTable("PostboxMessage")->deleteOutbox($this->session->getUserId());
        $this->redirect("@postbox_views?view=outbox");
        break;
      default:
        $this->forward404();
        break;
    }
  }
}