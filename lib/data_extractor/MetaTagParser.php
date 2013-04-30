<?php
/**
 * a parser for metatags
 */
class MetaTagParser {

  /**
   * Parse a given html for meta and title-tags
   *
   * @param string $pUrl
   * @return array $lValues
   */
  public static function parse($pHtml, $pUrl) {
    if (!preg_match("~<meta.*http-equiv\s*=\s*(\"|\')\s*Content-Type\s*(\"|\').*\/?>~", $pHtml)) {
      $pHtml = preg_replace('/<head[^>]*>/i','<head>
                             <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
                            ',$pHtml);
    }

  	try {
	    $lValues = array();
	    //supress html-validation-warnings
	    libxml_use_internal_errors(true);
	    $lDoc = new DOMDocument();
	    $lDoc->loadHTML($pHtml);

	    //get all meta-elements
	    $lTags = $lDoc->getElementsByTagName('meta');
	    //loop the metas
	    foreach ($lTags as $lTag) {
	    	//if attribute name isset make a new entry in an array with key=name and value=content
	      if ($lTag->hasAttribute('name')) {
	        $lName = strtolower($lTag->getAttribute('name'));
	        $lValues['meta'][$lName] = $lTag->getAttribute('content');
	      }
	    }

	    //get all title elements
	    $lTitles = $lDoc->getElementsByTagName('title');
	    //loop the titles
	    foreach ($lTitles as $lMetaTitle) {
	      $lTitle = $lMetaTitle->nodeValue;
	      //and save the value to an array with key=title. if a title is found, break the loop and continue
	      if($lTitle){
	      	$lValues['title'] = $lTitle;
	        continue;
	      }
	    }

	    //get all meta-elements
      $lLinks = $lDoc->getElementsByTagName('link');
      //loop the metas
      foreach ($lLinks as $lLink) {
        //if attribute name isset make a new entry in an array with key=name and value=content
        if ($lLink->hasAttribute('rel')) {
          $lName = $lLink->getAttribute('rel');
          $lValues['links'][$lName] = self::abslink($lLink->getAttribute('href'), $pUrl);
        }
      }

	    return $lValues;
  	}catch (Exception $e) {
      continue;
  	}
  }

  /**
   * returns the meta/title-elements found on a parsed html
   * @param string $pHtml
   */
  public static function getKeys($pHtml, $pUrl) {
    return self::parse($pHtml, $pUrl);
  }
  
  public static function abslink($imgSrc, $pageUrl)
  {
    $imgInfo = parse_url($imgSrc);
    if (! empty($imgInfo['host'])) {
      //img src is already an absolute URL
      return $imgSrc;
    }
    else {
      $urlInfo = parse_url($pageUrl);
      $base = $urlInfo['scheme'].'://'.$urlInfo['host'];
      if (substr($imgSrc,0,1) == '/') {
        //img src is relative from the root URL
        return $base . $imgSrc;
      }
      else {
        //img src is relative from the current directory
        return
          $base
          . substr($urlInfo['path'],0,strrpos($urlInfo['path'],'/'))
          . '/' . $imgSrc;
      }
    }
  }
}