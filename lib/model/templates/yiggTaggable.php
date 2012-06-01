<?php
class yiggTaggable extends Doctrine_Template
{
  /**
   * Returns a string suitable for pasting into an editform. to allow the
   * addition of pages, and conversion to and forth from tags->string.
   */
  public function getTagsAsString()
  {
    $invoker = $this->getInvoker();

    if($invoker->Tags->count() === 0)
    {
      return '';
    }

    $tagnames = array();
    foreach($invoker->Tags as $tag)
    {
      $tagnames[] = $tag->name;
    }
    return implode(', ',$tagnames);
  }

  /**
   * Adds a tag to the invoking object
   */
  public function addTags ( $tag_collection )
  {
    $invoker = $this->getInvoker();
    foreach( $tag_collection as $tag)
    {
      $key = $invoker->Tags->search($tag);
      if(false === $key)
      {
        $invoker->Tags->add($tag);
      }
      unset($key);
    }
  }

  /**
   * Updates tags for this object.
   *
   * @param String $tags
   */
  public function updateTags ( $tag_collection )
  {
    $invoker = $this->getInvoker();
    $invoker->addTags( $tag_collection );

    // Delete tags that have been removed.
    foreach( $invoker->Tags as $index => $tag )
    {
      $key = $tag_collection->search( $tag );
      if(false === $key)
      {
        // then the tag has been deleted.
        $invoker->Tags->remove($index);
      }
      unset($key);
    }
  }
}
