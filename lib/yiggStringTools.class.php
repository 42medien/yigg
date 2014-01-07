<?php
class yiggStringTools
{
  /**
   * Decodes a string encoded by javascript into utf8
   *
   * @static
   * @param String $value The value to decode
   * @return String the decoded string
   */
  public static function utf8_urldecode($value)
  {
    $value = rawurldecode($value);
    $value = preg_replace('/%([0-9a-f]{2})/ie', "chr(hexdec('\\1'))", $value);
    return $value;
  }


  /**
   * Replaces normal HTML-Entities and &#123-stuff as well
   * @static
   * @param $text
   * @return string
   */
  public static function html_entity_decode($text)
  {
    $text = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $text);
    return html_entity_decode($text, ENT_QUOTES);
  }
}