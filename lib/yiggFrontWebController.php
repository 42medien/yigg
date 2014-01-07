<?php

/**
 * yiggFrontWebController allows you to centralize your entry point in your web
 * application, but at the same time allow for any module and action combination
 * to be requested.
 *
 * @package    yigg
 * @subpackage controller
 */
class yiggFrontWebController extends sfFrontWebController
{
  /**
   * Internal parameters wich shouldn't be included in a url
   *
   * @var array List of parameters to hide from the url
   */
  protected $internalParameters = array(
    'feed',
    'rss'
  );

  /**
   * Log more details about forward problem
   */
  public function forward($moduleName, $actionName)
  {

    try
    {
      parent::forward($moduleName, $actionName);
    }
    catch(sfForwardException $e)
    {
      throw new sfForwardException(sprintf("Error forwarding to %s %s: %s",$moduleName, $actionName, $e->getMessage()));
    }
  }

  public function removeInvalidParams($params)
  {
    if(array_key_exists("sf_format",$params))
    {
      // pageid is irrelavent to rss.
      if( array_key_exists("pageid",$params))
      {
        unset($params['pageid']);
      }
    }

    foreach ($this->internalParameters as $parameterName)
    {
      if (true == array_key_exists($parameterName, $params))
      {
        unset($params[$parameterName]);
      }
    }
    return $params;
  }

  /**
   * Generates an URL from an array of parameters.
   * Removes any internal parameters if $parameters is a associative array.
   *
   * @param mixed   $parameters An associative array of URL parameters or an internal URI as a string.
   * @param boolean $absolute   Whether to generate an absolute URL
   *
   * @return string A URL to a symfony resource
   */
  public function genUrl($parameters = array(), $absolute = false)
  {
    if(true == is_array($parameters))
    {
      if(array_key_exists("sf_format",$parameters) && array_key_exists("pageid",$parameters))
      {
        unset($parameters['pageid']);
      }

      foreach ($this->internalParameters as $parameterName)
      {
        if (true == array_key_exists($parameterName, $parameters))
        {
          unset($parameters[$parameterName]);
        }
      }
    }
    else
    {
      $i=0;
      // firstpart.
      if(strpos($parameters,"?"))
      {
        // we only remove our parameters from this list.
        $route = substr($parameters,0, strpos($parameters,"?"));
        $params = explode("&",substr($parameters, strpos($parameters,"?")+1));

        // we need &'s to do this.
        if(count($params) > 1 )
        {
          if(strpos($parameters,"pageid") && strpos($parameters,"rss"))
          {
            $is_rss = true;
          }
          $pa = array_flip($this->internalParameters);

          foreach($params as $k =>$v)
          {
            if(substr($v, 0,stripos($v,"=") !== false))
            {
              if( array_key_exists( substr($v, 0,stripos($v,"=")),$pa) || (isset($is_rss) && "pageid" == substr($v, 0,stripos($v,"="))))
              {
                unset($params[$k]);
              }
            }
          }
          $parameters = $route . "?". implode("&",$params);
        }
      }
    }
    return parent::genUrl($parameters, $absolute);
  }

  /**
   * generates a minified url for a particular route. Always returns a absolute url
   * @param $params array
   * @return string
   */
  public function genMiniUrl($parameters)
  {
    $url = $this->genUrl($parameters, true);
    $redirect = RedirectTable::getByUrl($url);
    if($redirect === false)
    {
      $redirect = new Redirect();
      $redirect->url = $url;
      $redirect->save();
    }

    return $redirect->getMiniUri();
  }
}