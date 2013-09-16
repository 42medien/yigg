<?php
class Tag extends BaseTag
{
  /**
   * Creates tags from a string, given a comma seperated list,
   * should support quotes, hypens and return the list of tags.
   * This function could prove to be quite a bottle neck. We do all the
   * processing whilst creating the story, to minimalise load on the system.
   *
   * Since tags are heavily indexed, it should be pretty fast to get and
   * create these tags.
   *
   * @return Doctrine_Collection
   */
  static function createTagsFromString( $string )
  {
    // Remove commas and trim spaces.
    $pattern = "[^A-ZäöüÄÖÜßa-z0-9,-. ]";
    $tags = preg_replace( $pattern ,'',  $string );
    $tags = explode(",", $tags  );
    $tags = array_unique( $tags );

    if( count($tags) > 0 )
    {
      return self::createTagsFromArray( $tags );
    }
    else
    {
      return array();
    }
  }

  /**
   * Creates a doctrine collection of tags from an array of tags.
   * This function creates tags that don't exist yet.
   *
   * @param array $tags the tags you wish to be in the collection.
   *
   * @return Doctrine_Collection
   */
  static function createTagsFromArray($tags)
  {
    // add or link them
    $compiledTags = new Doctrine_Collection("Tag");
    foreach($tags as $tag)
    {
      if($tag)
      {
        // Remove commas and trim spaces.
        $tag = trim($tag);
        $pattern = "[^A-ZäöüÄÖÜßa-z0-9,-. ]";
        $tag = preg_replace( $pattern ,'',  $tag );

        if( '' != $tag)
        {
          // Check to see if we have a tag by that name already
          $t = TagTable::getTable()->findOneByName( $tag );

          // else create a new tag
          if( true == empty($t) )
          {
            $t = new Tag();
            $name = trim( $tag );
            if( '' != $name )
            {
              $t->name = $name;
              $t->save();
            }
          }
          // t is either new or from the db.
          $compiledTags->add( $t , $t['id'] );
        }
      }
    }
    return $compiledTags;
  }

  /**
   * Returns the routing link for displaying this object.
   */
  public function getLink()
  {
    return "@tag?tags={$this->name}";
  }

  public function getSpeculationLink()
  {
    return '@speculation_tagged?tag=' . $this->name;
  }
  /**
   * Allows usage of echo $tag
   */
  public function __toString()
  {
    return $this->name;
  }
}