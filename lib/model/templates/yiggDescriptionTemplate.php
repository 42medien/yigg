<?php

class yiggDescription extends Doctrine_Template
{
  /**
   * Set table definition for SoftDelete behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->addListener( new yiggDescriptionListener() );
  }

  public function getPlainTextDescription()
  {
    $raw_description = htmlspecialchars_decode($this->getInvoker()->description);
    $raw_description = strip_tags($raw_description);

    $escaped_description = htmlspecialchars($raw_description, null, null, false);
    return $escaped_description;
  }

  /**
   * Returns the presentation description
   */
  public function getPresentationDescription()
  {
    return $this->prepareXhtml($this->getPlainTextDescription());
  }

  /**
   * returns content summary
   */
  public function getDescriptionSummary($length = 350)
  {
    return $this->generateSummary($this->getPlainTextDescription(), $length);
  }

  /**
   * Prepares Xhtml (Adds smilies / profile links / unescapes)
   */
  private function prepareXhtml($xhtml) {
    $xhtml = yiggTools::replaceSmilies($xhtml);
    $xhtml = yiggTools::addProfileLinks($xhtml);
    $xhtml = nl2br($xhtml);
    return $xhtml;
  }

  /**
   * Generates a summary for a given string
   * @param string $string String to summarize
   * @param int $limit Char-Limit for Summary
   * @return string
   */
  public function generateSummary($string, $limit=500)
  {
    if( mb_strlen($string,"UTF-8") >= $limit)
    {
      $string = mb_substr($string, 0, $limit,"UTF-8");

      $endOfLastSentence = mb_strrpos($string, '.',null,"UTF-8");
      $lastSentenceEndsNearEnd = false !== ($endOfLastSentence) && $endOfLastSentence > $limit-100;

      if($lastSentenceEndsNearEnd)
      {
        $string = mb_substr($string, 0, $endOfLastSentence + 1,"UTF-8");
      }
      else
      {
        $endOfLastWord = mb_strrpos($string, ' ',"UTF-8");
        $string = mb_substr($string, 0, (false !== $endOfLastWord ? $endOfLastWord : 500 ), "UTF-8")  .  "... ";
      }
    }
    return trim($string);
  }
}
