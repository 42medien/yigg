<?php

/**
 * yiggValidatorTag validates a tag string. It also converts the input value to valid Tag collection.
 *
 * @package    Yigg
 * @subpackage validator
 * @author     caffeine
 *
 */
class yiggValidatorTag extends yiggValidatorPlainText
{
  /**
   * Configures the current validator.
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options = array(), $messages = array());
    $this->addMessage('no_tags', 'Sorry, We couldnt match any tags from: %value%');
    $this->addMessage('too_long', 'Sorry, The tag &quot; %value% &quot; is too long');
    $this->addMessage('too_short', 'Sorry, The tag &quot; %value% &quot; is too short');
    $this->addMessage('to_many_tokens_per_tag', 'Ein Tag enthält mehr als 3 Wörter - benutzt du Kommas?');
  }

  /**
   * Cleans the input and ensures we have a valid collection of tags.
   *
   * @return Doctrine_Collection
   */
  protected function doClean($value)
  {
    parent::doClean($value);

    $pattern = "[^A-Za-zäöüÄÖÜß0-9,-. ]";
    $string = preg_replace( $pattern ,'', $value );
    $string = preg_replace('/[ ]+/', ' ', $string); # Replace more then one space

    $tags = explode(",", $string );
    $tags = array_unique($tags);
    $singletag = "/[\p{Ll}\p{Lu}\p{Nd}][ \p{Ll}\p{Lu}\p{Nd}.-]{0,30}[\p{Ll}\p{Lu}\p{Nd}]/";
    foreach($tags as $key => $tag)
    {
      if(0 === preg_match($singletag, $tag))
      {
        unset($tags[$key]);
      }
    }


    // Check to make sure each tag is under the limit.
    if(is_array($tags) && count($tags > 0))
    {
      foreach( $tags as $tag )
      {
        $tag = trim($tag);
        $length = mb_strlen($tag,'UTF-8');
        if( $length > 128 )
        {
          throw new sfValidatorError($this, 'too_long', array('value' => strip_tags(trim( substr($tag,0,20). "... ")), ENT_NOQUOTES, 'UTF-8'));
        }

        if(count(explode(" ", $tag)) > 6) # Check if a single tag contains more then 3 tokens
        {
          throw new sfValidatorError($this, 'to_many_tokens_per_tag');
        }
      }

      $tags = yiggTools::removeStopWords($tags);

      if( count($tags) > 0 )
      {
        $tag_collection = Tag::createTagsFromArray( $tags );
        if( count($tag_collection) > 0 )
        {
          return $tag_collection;
        }
      }
    }

    throw new sfValidatorError($this, 'no_tags', array('value' => $value) );
  }
}
