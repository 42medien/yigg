<?php
class commentActions extends yiggActions
{
  /**
   * Sets up the object for the controller to manipulate and aplly the
   * behavior to.
   *
   * @see apps/frontend/lib/yiggActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    $request = sfContext::getInstance()->getRequest();

    // we can only accept it if we know the object in question.
    $obj_id = $request->getParameter("id", false );
    switch( yiggTools::parseString($request->getParameter("object")) )
    {
      case "story":
        $this->obj = Doctrine::getTable("Story")->findOneById( (int) $obj_id );
      break;

      case "comment":
        // No related object we have to care about
      break;

      default:
        $this->getResponse()->setStatusCode(403);
        return sfView::ERROR;
      break;
    }
  }

  public function executeCreate( $request )
  {
    if( false === $this->obj )
    {
      $this->getResponse()->setStatusCode(400);
      $this->forward404If(false === $this->comment,"Das Objekt wurde nicht gefunden oder wurde gelöscht.");
      return sfView::ERROR;
    }

    $this->form = new FormUserComment();

    if(false === $request->isMethod("post"))
    {
        if(true === $request->isAjaxRequest())
        {
          $this->renderPartial(
            "formComments", array(
              "form"=> $this->form,
              "comment" => $this->comment,
              "obj"=>  $this->obj
            )
          );
          return sfView::NONE;
        }

        $this->comments = CommentTable::getStoryCommentQuery($this->obj->id)->execute();
        return sfView::SUCCESS;
    }
    
    if( true === $this->form->processAndValidate() )
    {
      $conn = Doctrine::getTable("Comment")->getConnection();
      $conn->beginTransaction();

      $this->comment = new Comment();
      $this->comment->fromArray($this->form->getValues());
      $this->comment->user_id = $this->session->getUserId();
      $this->obj->Comments->add($this->comment);
      $this->obj->save($conn);
      
      $conn->commit();
    }

    if( true === $this->isAjaxRequest() )
    {
      $this->comments = CommentTable::getStoryCommentQuery($this->obj->id)->execute();
      return sfView::SUCCESS;
    }

    if((int) $this->comment['id'] >= 0)
    {
      return $this->redirect( $this->obj->getLink() . "#Comment_" . $this->comment['id']);
    }

    return sfView::ERROR;
  }

  /**
   * Delete a comment for a object.
   * @param yiggWebRequest
   */
  public function executeDelete( $request )
  {
    $this->comment = Doctrine::getTable("Comment")->findOneById( (int) $request->getParameter("id"));
    $this->forward404If(false === $this->comment,"Der Kommentar wurde nicht gefunden oder wurde gelöscht.");

    if( true === $this->comment->hasDeletePermissions( $this->session->getUser()) )
    {
      $this->comment->delete();
    }

    if( true === $this->isAjaxRequest() )
    {
      return sfView::SUCCESS;
    }

    return $this->redirect( $this->comment->getParentObj()->getLink() );
  }
}