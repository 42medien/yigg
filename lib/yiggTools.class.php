<?php

/**
 * This is a library for functions that are used sitewide, that are independant
 * of classes, and need some nice encapsulator so we can test them with
 * functional tests.
 *
 * @package     yigg
 * @subpackage  helpers
 */
class yiggTools
{

  /**
   * Creates a tweet link, with a title and link. complete with a
   * link to twitter status.
   *
   * @param $title
   * @param $link the absolute link of to be created.
   * @param $shorten_url should we send the url to tinyURl first
   * @return unknown_type
   */
  public static function createTweetText($title, $link, $shorten_url = true)
  {
    $appendix_length = mb_strlen($link) + mb_strlen(" @yigg") + 1;

    $twitterMessage = mb_substr($title, 0,  140 - $appendix_length ,'utf-8') . ' ' . $link . " @yigg";
    return $twitterMessage;
  }

  public static function createTweetUrl($title, $link, $use_clients=true)
  {
    return sfConfig::get("app_twitter_status_url") . urlencode(self::createTweetText($title, $link));
  }


  /**
   * Creates a status link for facebook.
   *
   * @param $title
   * @param $link
   * @return unknown_type
   */
  public static function createFacebook($title, $link)
  {
    $message = mb_substr( $title, 200 - mb_substr($title, 0,  200   - mb_strlen($link) ,'utf-8'));

    $facebook_url = sfConfig::get("app_facebook_status_url");
    return $facebook_url . "?u=". urlencode($link)."&t=" . urlencode(trim($message));
  }



 /**
  * Filters out bad URI's by checking to see if they follow the correct
  * syntax, and do not have any bad characters.
  * @param string url - the URL to check
  * @return boolean - true if the url is properlly escaped, and formated,
  * true if the URL is proper
  */
  public static function isProperURL($url)
  {
    $strRegex = "{^(https?://)"
        . "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //user@
        . "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP- 199.194.52.184
        . "|" // allows either IP or domain
        . "([0-9a-z_!~*'()-]+\.)*" // tertiary domain(s)- www.
        . "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // second level domain
        . "[a-z]{2,6})" // first level domain- .com or .museum
        . "(:[0-9]{1,4})?" // port number- :80
        . "((/?)|" // a slash isn't required if there is no file name
        . "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$}is";
    $matches = preg_match( $strRegex , $url );
    return $matches === 1;
  }

 /**
  * returns the difference between the value and the current time.
  * @return string x mins/hours/days
  * @param timestamp Unix timestamp
  */
  public static function timeDiff($value)
  {
    $ago = abs(time() - strtotime($value));

    if($ago < 60)
    {
       $span = $ago;
       return ($span != 1) ? $span." Sekunden" : " einer Sekunde";
    }
    if($ago < 3600)
    {
      $span = round($ago/60);
      return ($span != 1) ? $span." Minuten" : "einer Minute";
    }
    if($ago < 86400)
    {
      $span = round($ago/3600);
      return ($span != 1) ? $span." Stunden" : "einer Stunde";
    }
    if($ago < 2592000) // 86400*30 (Month)
    {
      $span = round($ago/86400);
      return ($span != 1) ? $span." Tagen" : " einem Tag";
    }

    if($ago < 31536000) // 86400*365 (time<Year)
    {
      $span = round($ago/2592000);
      return ($span != 1) ? $span." Monaten" : " einem Monat";
    }

    if($ago >= 31536000) //86400*365 (time>=Year)
    {
      $span = round($ago/31536000);
      return ($span != 1) ? $span." Jahren" : " einem Jahr";
    }
  }

  /**
   * calculates the given / actual time rounded to the last 15 minutes
   *
   * @param  integer  $ref  timestamp
   */
  public static function getRoundedTime($ref = null)
  {
    $min = date('i', ($ref === null ? time() : $ref));
    switch($min)
    {
      case $min < 15:
        $min = '00';
      break;
      case $min < 30:
        $min = 15;
      break;
      case $min < 45:
        $min = 30;
      break;
      case $min < 60:
        $min = 45;
      break;
      default:
        $min = '00';
      break;
    }
    return date('Y-m-d H:', $ref) . $min;
  }

  /**
   * Adds @profile_links to presentation descriptions.
   *
   * @param String $str
   * @return String
   */
  public static function addProfileLinks( $description )
  {
    // means we never have to change this function if the routing to userPublicProfile ever changes.
    $base = sfContext::getInstance()->getController()->genUrl("@user_public_profile?username=marvin");
    $baseurl = str_replace("marvin","",$base);
    $result = preg_replace('/@([a-zA-Z0-9_\.-]+)/','<a href="'.$baseurl.'$1" title="">$0</a>', $description );
    return $result;
  }


  /**
   * Finds smiles and respect prefix and suffix, replaces them using innerRelaceSmies as callback-function
   * @param String description
   * @return String description with smilies
   */
  public static function replaceSmilies( $description )
  {
    $smilies = self::prepareSmilieArray($withPrefixAndSuffix = true);
    return preg_replace_callback(array_keys($smilies), array("yiggTools", "innerReplaceSmilies"), $description);
  }

  /**
   * Replaces smilies and preserves prefix and suffix
   * @param Array $match
   * @returns String Replaced smilies +
   */
  private static function innerReplaceSmilies($matches)
  {
    $smilies = self::prepareSmilieArray($withPrefixAndSuffix = false);
    return preg_replace(array_keys($smilies),array_values($smilies), $matches[0]);
  }

  /**
   * Construct smilie regex and replacements from configuration
   * @param Boolean $withPrefixAndSuffix
   * @return Array of regex => smilies
   */
  private static function prepareSmilieArray($withPrefixAndSuffix = false)
  {
    require_once(sfContext::getInstance()->getConfigCache()->checkConfig('config/smilies.yml'));
    $options = sfConfig::get("smilies_smilies_options", array());
    $smilies = sfConfig::get("smilies_smilies_smilies", array());

    foreach($smilies as $image => $regex)
    {
      if(true === $withPrefixAndSuffix)
      {
        $smilies[$image] = $options['prefix'] . $regex . $options['suffix'];
      }
      $smilies[$image] = "%".$smilies[$image]."%";
    }

    $smilies = (array_flip($smilies));

    foreach($smilies as $regex => $image)
    {
      $smilies[$regex] = img_tag('smilies/'.$image);
    }

    return $smilies;
  }

  /**
   * returns timestamp in db date format (d.m.Y h:m:s)
   * by default return current time
   *
   * @param  string    $format
   * @param  integer    $timestamp
   * @return   string     formated timestamp
   *
   */
  public static function getDbDate($format = null, $timestamp = null)
  {
    if(!isset($timestamp))
    {
      $timestamp = time();
    }

    if(!isset($format)){
      $format = "Y-m-d H:i:s";
    }

    return date($format, $timestamp);
  }

  /**
   * Transforms text from a particular representation to a 7 bit representation
   * @param string $text (the text to transform)
   * @param string $from_enc (encoding type of $text, e.g. UTF-8, ISO-8859-1)
   * @return 7bit representation of the
   * @author Robert Curth <curth@yigg.de>
   */
  public static function to7bit( $text, $from_enc = "UTF-8")
  {
      $text = mb_convert_encoding( $text, 'HTML-ENTITIES', $from_enc);
      $replacements = array(
        '/&(Alpha);/' => "$1",
        '/&(Beta);/' => "$1",
        '/&(Gamma);/' => "$1",
        '/&(Delta);/' => "$1",
        '/&(Epsilon);/' => "$1",
        '/&(Zeta);/' => "$1",
        '/&(Eta);/' => "$1",
        '/&(Theta);/' => "$1",
        '/&(Iota);/' => "$1",
        '/&(Kappa);/' => "$1",
        '/&(Lambda);/' => "$1",
        '/&(Mu);/' => "$1",
        '/&(Nu);/' => "$1",
        '/&(Xi);/' => "$1",
        '/&(Omicron);/' => "$1",
        '/&(Pi);/' => "$1",
        '/&(Rho);/' => "$1",
        '/&(Sigma);/' => "$1",
        '/&(Tau);/' => "$1",
        '/&(Upsilon);/' => "$1",
        '/&(Phi);/' => "$1",
        '/&(Chi);/' => "$1",
        '/&(Psi);/' => "$1",
        '/&(Omega);/' => "$1",
        '/&(alpha);/' => "$1",
        '/&(beta);/' => "$1",
        '/&(gamma);/' => "$1",
        '/&(delta);/' => "$1",
        '/&(epsilon);/' => "$1",
        '/&(zeta);/' => "$1",
        '/&(eta);/' => "$1",
        '/&(theta);/' => "theta",
        '/&(iota);/' => "$1",
        '/&(kappa);/' => "$1",
        '/&(lambda);/' => "$1",
        '/&(mu);/' => "$1",
        '/&(nu);/' => "$1",
        '/&(xi);/' => "$1",
        '/&(omicron);/' => "$1",
        '/&(pi);/' => "$1",
        '/&(rho);/' => "$1",
        '/&(sigmaf);/' => "$1",
        '/&(sigma);/' => "$1",
        '/&(tau);/' => "$1",
        '/&(upsilon);/' => "$1",
        '/&(phi);/' => "$1",
        '/&(chi);/' => "$1",
        '/&(psi);/' => "$1",
        '/&(omega);/' => "$1",
        '/&(thetasym);/' => "theta",
        '/&(upsih);/' => "$1",
        '/&(piv);/' => "pi",
        '/&copy;/' => "copyright",
        '/&deg;/' => 'degrees',
        '/&prime;/' => 'feet',
        '/&euro;/' => 'euro',
        '/&cent;/' => 'cent',
        '/&pound;/' => 'pound',
        '/&yen;/' => 'yen',
        '/&szig;/' => 'ss',
        '/&([cCaouAyYiIOUeENn])(cedil|tilde|ring|grave|circ|acute|stroke|caron|slash);/' => '$1',
        '/&([aouiIAOUyYeE])(uml|lig);/' => '$1'.'e',
        '/&OElig;/' => 'Oe',
        '/&oelig;/' => 'oe',
        '/&AElig;/' => 'Ae',
        '/&(aelig);/' => 'ae',
        '/&(szlig);/' => 'ss',
        '/&(.)[^ ;]*;/' => '',
        '/\/+/' => 'slash',
    );
    return  preg_replace( array_keys($replacements), array_values($replacements) , $text );
  }

 /**
   * Creates a salt to register a user with
   * our statistics provider.
   *
   * @return String sha1 hash.
   */
  public static function createMclientSalt( $username )
  {
    return sha1( $username . self::randomStringGenerator());
  }

  /**
   * Generates a random string for use when generating salts
   * with passwords etc.
   */
  public static function randomStringGenerator()
  {
    $handle = fopen ('/dev/urandom', 'r');
    if (isset( $handle) )
    {
      $data = fread ($handle, 0xFF);
      if (isset($data))
      {
       $data = unpack ('H*hex', $data);
      }
      fclose ($handle);
    }

    if(!array_key_exists('hex',$data))
    {
      $data['hex'] = mt_rand();
    }
    return $data['hex'];
  }


  /**
   * parseString
   * Parses a string and removes all characters but Letters, numbers and spaces,
   * bad characters are replaced with spaces.
   *
   * @param String $string the string to parse
   * @return string Parsed String
   */
  public static function parseString( $string )
  {
    return preg_replace( "[^A-Za-z0-9\s]" , ' ', $string );
  }

   /**
   * Transforms any string into the format used to store fields uniquely in
   * the database.
   * Any logic relating to how the username is presented should be added to
   * this function.
   *
   * @param string - the string you wish to transform
   * @return string - the reformatted string
   */
  static function stringToUsername($string)
  {
    // Make username lowercase
    $string = strtolower($string);
    $string = str_replace(" ","_",$string);
    $pattern = "/[^a-z0-9_\.-]/"; // Username only alowed characters 0 - 9 a - z and underscores.
    $string = preg_replace($pattern,'',$string);
    $string = substr(trim($string), 0, 32);
    return $string;
  }

  /**
   * Check DNSBL - WIN also - DD
   */
  static function blacklisted($ip)
  {
    $dnsbl_lists = array("bl.spamcop.net", "list.dsbl.org", "sbl.spamhaus.org");

    if ($ip && preg_match('/^([0-9]{1, 3})\.([0-9]{1, 3})\.([0-9]{1, 3})\.([0-9]{1, 3})/', $ip))
    {

      $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
      $on_win = substr(PHP_OS, 0, 3) == "WIN" ? 1 : 0;

      foreach ($dnsbl_lists as $dnsbl_list)
      {
        if (function_exists("checkdnsrr"))
        {
          if (checkdnsrr($reverse_ip . "." . $dnsbl_list . ".", "A"))
          {
            return $reverse_ip . "." . $dnsbl_list;
          }
        }
        else if ($on_win == 1)
        {
          $lookup = "";
          @exec("nslookup -type=A " . $reverse_ip . "." . $dnsbl_list . ".", $lookup);
          foreach ($lookup as $line)
          {
            if (strstr($line, $dnsbl_list))
            {
            return $reverse_ip . "." . $dnsbl_list;
            }
          }
        }
      }
    }
    return false;
  }


  /**
   * Returns the size of a shorthand size definition in byte
   *
   * @param string $value Shorthand size definition
   * @return int Size in byte
   */
  public static function shortToBytes($value)
  {
    $suffix = strtolower($value{strlen($value) - 1});
    $value  = intval(substr($value, 0, strlen($value) - 1));

    switch ($suffix)
    {
      case 'g':
        $value *= 1024;
      case 'm':
        $value *= 1024;
      case 'k':
        $value *= 1024;
    }

    return (int) $value;
  }


  /**
   * Turns a MySQL date into a ISO 8601 date
   *
   * @param string $mysql_date A MySQL date time value
   * @return string A ISO 8601 date time value or false on conversion error
   */
  public static function convertToIso8601($mysql_date)
  {
    $time = strtotime($mysql_date);

    if (false === $time)
    {
      return false;
    }

    return gmdate('Y-m-d\TH:i:s\Z', $time);
  }

  /**
   * Turns a ISO 8601 date into a MySQL date
   *
   * @param string $iso_date A ISO 8601 date time value
   * @return string A MySQL date time value or false on conversion error
   */
  public static function convertFromIso8601($iso_date)
  {
    $pattern = ''
      .'^([0-9]{4})\-?([0-9]{2})\-?([0-9]{2})'
      .'T([0-9]{2}):?([0-9]{2}):?([0-9]{2})'
      .'(Z|((\+|\-)([0-9]{2}):?([0-9]{2})))?$';

    if (false === ereg($pattern, $iso_date, $register))
    {
      return false;
    }
  }

  /**
   * Extract @usernames within a string and returns an array of the Users
   * @param A string
   * @return User[]
   */
  public static function getAdressedUsers($text)
  {
    $text = self::removeQuotedContent($text);
    $usernames = self::getAdressedUsernames($text);

    $users = array();
    foreach($usernames as $username)
    {
      $username = yiggTools::stringToUsername($username); //Clean usernames
      $user = UserTable::getTable()->findOneByUsername( $username );

      if($user !== false)
      {
        $users[] = $user;
      }
    }
    return $users;
  }

  public static function getAdressedUsernames($text)
  {
    preg_match_all("/@([a-zA-Z0-9_\.-]+)/", $text , $usernames);
    if(is_array($usernames) && array_key_exists(1, $usernames))
    {
      return $usernames[1];
    }
    else
    {
      return array();
    }
  }

  public static function removeQuotedContent($text)
  {
    $quote_regex = "/\[quote(.*?)\[\/quote\]/i";
    $text = preg_replace($quote_regex, "", $text);
    return $text;
  }

  /**
   * Get nouns from a string
   *  @param string
   *  @array array of the noums
   */
   public static function extractNouns($string)
   {
     $string = str_replace(array(":", "-", '".', "|"), " ", $string);
     $words = explode(" ", $string);
     if(count($words) == 0)
     {
       return $nouns;
     }

     $nouns = array();
     foreach($words as $word)
     {
       $noun_pattern = "/^[A-ZÄÖÜ].[a-zäöüß-]+$/";
       if(preg_match($noun_pattern, $word) > 0)
       {
         $nouns[] = strtolower($word);
       }
     }

     $good_words = count($nouns) > 0 ? self::removeStopWords($nouns) : $nouns;
     return $good_words;
   }

   /**
    * Removes the tags from an array where
    * the words exist in a stopword db.
    *
    * @param $words Array
    * @return Array
    */
   public static function removeStopWords($words)
   {
     $bad_words = Doctrine::getTable("StopWords")
        ->getQueryObject()
        ->select("word")->from("StopWords INDEXBY word")
        ->whereIn("word", $words)
        ->execute(array(),Doctrine::HYDRATE_ARRAY);

      $good_words = array_diff( $words, array_keys($bad_words));
      return $good_words;
   }

   /**
    * Finds urls in string
    * @static
    * @param  $text
    * @return array|null
    */
   public static function findUrlsInString($text)
   {
     preg_match_all('/((http|https):\/\/[^\s$<\"]+)/si', $text, $matches);

     if(empty($matches[0]))
     {
       return null;
     }
     $urls = array();
     foreach($matches[0] as $match)
     {
       $urls[] = trim($match);
     }

     return $urls;

   }

   public static function linkUrlsWithShortUrls($text)
   {
     $urls = self::findUrlsInString($text);
     if(empty($urls))
     {
       return $text;
     }

     foreach($urls as $url)
     {
       $redirect = Redirect::createOrGetIfExists($url);
       $replacement = sprintf('<a href="%s">%s</a>',
          $redirect->getMiniUri(),
          parse_url($url, PHP_URL_HOST));
       
       $text = str_replace($url, $replacement, $text);
     }
     return $text;
   }
}