<?php

function javascript_tag_open(&$closingTag, $options = array())
{
  $options = array_merge(array('type' => 'text/javascript'), $options);
  $contentTypeParts = explode(';', sfContext::getInstance()->getResponse()->getContentType());

  $openingTag = sprintf ('<script%s>', _tag_options($options));

  if ((true == is_array($contentTypeParts))
    && ('application/xhtml+xml' == array_shift($contentTypeParts)))
  {
    $openingTag .= "<![CDATA[\n";
    $closingTag  = "]]>";
  }
  else
  {
    $openingTag .= "/*<![CDATA[*/\n";
    $closingTag  = "/*]]>*/";
  }

  $closingTag .= "</script>\n";

  return $openingTag;
}