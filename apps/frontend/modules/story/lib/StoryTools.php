<?php

/**
 * StoryTools:
 *
 * Specific tools which are used for the populating and modifying the Story objects.
 *
 */
class StoryTools
{
  protected $client, $error;

  /**
   *
   * @return
   * @param $url Object
   */
  public function fetchTitleFromUrl( $url )
  {
    // get the title and a portion of the content.
    $this->client = new yiggHttpClient();
    $this->client->get( $url );

    if( $this->client->hasResponse() )
    {
      return $this->findTitleInResponse();
    }
    return false;
  }

  /**
   * findTitleInResponse: Finds the values of the <title> tag via an yiggHTTPClient object.
   *
   * @return String the title of the response, or false if no title can be found.
   * @param $httpClient Object
   */
  public function findTitleInResponse()
  {

    $headers = $this->client->getHeader();
    $body = $this->client->getBody();

    // Find the title via Regular expression (more stable) + 2xx codes (ok)
    if( preg_match('{<title[^>]*>(.+?)<\/title>}is', $body, $matches ) && ("2" === substr($headers['http-status'],0,1) ))
    {
    	
      $title = $matches[1];

      $title = trim($title);
      $title = preg_replace("/\s+|\t+/"," ", $title);
      $title = mb_convert_encoding($title ,'UTF-8','UTF-8,ISO-8859-1,ASCII,JIS,EUC-JP,SJIS,UTF-7');
      
      $title = htmlspecialchars_decode($title); #In the original source & apears as &amp. so we have to convert them to text. They will be encoded by our system later.
      $title = strip_tags($title); # Stripitng tags last - decoding might decode html-tags as well. 
      return $title;
    }
    return false;
  }

  /**
   * returns the error message if we have an error.
   *
   */
  public function getMessage()
  {
    $headers = $this->client->getHeader();
    switch( (int) $headers['http-status'] )
    {
      case 404:
        return '404: Diese Seite wurde nicht gefunden. Bitte überprüfe die Adresse.';
      break;

      case 500:
        return "Diese Seite hat eine Fehlernachricht zurückgegeben. Bitte überprüfe die Adresse.";
      break;

      default:
        return $this->client->statusTexts[$headers['http-status']];;
      break;
    }
  }

  /**
   * tells if the client had a fatal error or not. (400 or 500 series)
   */
  public function hasFatalError()
  {
    $headers = $this->client->getHeader();
    if( substr((int) $headers['http-status'],0,1) === 4 || substr((int) $headers['http-status'],0,1) === 5 )
    {
      return true;
    }
    return false;
  }

  public function __toString()
  {
    return $this->getMessage();
  }
}
