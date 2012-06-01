<?php
class commentComponents extends sfComponents
{
  public function executeCommentList()
  {
    $this->comments = Doctrine::getTable("Comment")->getCommentsOnStory($this->obj->id);
    $this->form = new FormUserComment();
    return sfView::SUCCESS;
  }

  public function executeLatestComments()
  {
    $this->comments = CommentTable::getLatestComments(10);
  }
}