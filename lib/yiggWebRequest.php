<?php

/**
 *
 * @package     yigg
 * @subpackage  helpers
 */
class yiggWebRequest extends sfWebRequest
{
  protected $ninjaUpdater;
  protected $isMobile = null;

  public function getAction()
  {
    return $this->getParameterHolder()->get("action");
  }

  public function getModule()
  {
    return $this->getParameterHolder()->get("module");
  }

  /**
   * Returns the current module as action as one string
   * @return void
   */
  public function getModuleAction()
  {
    return "{$this->getModule()}/{$this->getAction()}";
  }

  /**
   * tells you if the request is a rss feed.
   * @return Boolean
   */
  public function isRss()
  {
    return $this->getParameter("sf_format") === "atom";
  }

  /**
   * Returns the current Requests IP address.
   * @return String ipaddress
   */
  public function getRemoteAddress()
  {
    $pathArray = $this->getPathInfoArray();
    return $pathArray['REMOTE_ADDR'];
  }

  /**
   * Force the rendering engine to use HTML.
   * will want to change this later if we decide
   * to support differnet content types.
   */
  public function getRequestFormat()
  {
    if (is_null($this->format))
    {
      if(true == $this->hasParameter('sf_format') && "atom" == $this->getParameter('sf_format') || "html" == $this->getParameter('sf_format') )
      {
        $this->setRequestFormat($this->getParameter('sf_format'));
      }
    }
    return $this->format;
  }

  /**
   * Returns the current user agent.
   *
   * @return unknown
   */
  public function getUserAgent()
  {
    return $this->getHttpHeader('User-Agent');
  }

  /**
   * Will let you know if the current request has a security token.
     * @return Boolean
   */
  public function hasSecurityToken()
  {
    $pathArray = $this->getPathInfoArray();
    return isset($pathArray['HTTP_CONTENT_TOKEN']);
  }

  /**
   * Gets the current security token from the request
   * @return String (the current csrf token)
   */
  public function getSecurityToken()
  {
    $pathArray = $this->getPathInfoArray();
    return $pathArray['HTTP_CONTENT_TOKEN'];
  }

  /**
   * Returns true if the request is an XmlHTTPrequest.
   * @return boolean
   */
  public function isAjaxRequest()
  {
    return $this->isXmlHttpRequest();
  }

  /**
   * Returns the current ninjaUpdater for this request
   * @return Object NinjaValidator
   */
  public function getNinjaUpdater()
  {
    if( isset($this->ninjaUpdater) )
    {
      return $this->ninjaUpdater;
    }
    else
    {
      $this->ninjaUpdater = new yiggNinjaUpdater();
      return $this->ninjaUpdater;
    }
  }

  /**
   * returns true or false if the referer is from google.
   */
  public function fromGoogle()
  {
    return 0 !== preg_match("/www\.google\.(.*?)/", $this->getReferingDomain());
  }

  /**
   * Returns the hostname of the refering domain
   * @return String Hostname / Null if no domain
   */
  public function getReferingDomain()
  {
    if(is_null($this->getReferer()))
    {
      return;
    }
    return parse_url($this->getReferer(), PHP_URL_HOST);
  }

  /**
   * Returns an array of the Google Keywords
   * @return Array of String Google Searchterms, false if no keywords
   *
   * example $url = 'http://www.google.de/search?hl=en&q=site%3Awww.yigg.de+youporn&btnG=Google+Search&aq=f&oq=';
   */
  public function getGoogleKeywords()
  {
    if( $this->fromGoogle() && preg_match("{^https?://(?:\w*\.)?google\.\w{2,3}/.*(?:\?|&)q=([^&]+)(?:&|$)}", $this->getReferer() , $buf))
    {
      $keywords = urldecode($buf[1]);
    }
    else
    {
      $keywords = false;
    }
    return $keywords;
  }

  /**
   * Forces a parameter to be held.
   *
   * @param array() $newParams to force
   * @return absolute url
   */
  public function getParams()
  {
    $routes = sfContext::getInstance()->getRouting()->getRoutes();
    $defaultParams = $routes[sfContext::getInstance()->getRouting()->getCurrentRouteName()]->getDefaultParameters();
    $requiredParams = $routes[sfContext::getInstance()->getRouting()->getCurrentRouteName()]->getRequirements();

    $curParams = $this->getParameterHolder()->getAll();
    foreach($curParams as $key => $param)
    {
      if( false === array_key_exists($key, $defaultParams) && false === array_key_exists($key, $requiredParams))
      {
        unset($curParams[$key]);
      }
    }

    return $curParams;
  }


  /**
   * check the user Agent for signs of a mobile device
   * switch is faster than if blocks. and we can extend
   * this to store mobile version if needed.
   *
   * @return boolean
   */
  public function isMobile()
  {
    if(null !== $this->isMobile)
    {
      return $this->isMobile;
    }

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $http_accept = $_SERVER['HTTP_ACCEPT'];
    switch(true)
    {
      case (preg_match('/(android|opera mini|blackberry)/i',$user_agent)):
      case (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent)):
      case (preg_match('/(windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent)):
      case (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|pda|psp|treo)/i',$user_agent)):
      case ((strpos($http_accept,'text/vnd.wap.wml')>0)||(strpos($http_accept,'application/vnd.wap.xhtml+xml')>0)):
      case (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])):
      case (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','comp'=>'comp','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','tosh'=>'tosh','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',))):
        $this->isMobile = true;
      break;
      default:
        $this->isMobile = false;
      break;
    }

    return $this->isMobile;
  }

  /**
   * @return Checks if this device is an iPod, IPad, IPhone
   */
  public function isIDevice()
  {
    if(true === $this->isMobile())
    {
      return false;
    }
    return false !== strpos($this->getUserAgent(), "iPad") || false !== strpos($this->getUserAgent(), "iPhone");
  }

  //@todo WHAT IS THIS? FIX ON THIS FRIDAY!!
  public function getDefaultParams()
  {
    $routing = sfContext::getInstance()->getRouting();
    $defaultParameters = $routes[$routing->getCurrentRouteName()]->getDefaultParameters();
    return $defaultParameters;
  }

  /**
   * Forces the parameters for a route which is generated for you.
   *
   * @param $newParams Array
   * @return String new route..
   */
  public function forceParams($newParams)
  {
    $routing = sfContext::getInstance()->getRouting();
    $route = $routing->generate(
      null,
      sfContext::getInstance()->getController()->removeInvalidParams(
        array_merge(
          $this->getParams(),
          $newParams
        )
      )
    );
    return $route;
  }

  /**
   * Checks if this request was made by a crawler
   * USE WITH CARE THIS IS RELATIVELY RESSOURCE INTENSIVE!
   * @return true if crawler false if normal user-agent
   */
  public function isCrawler($fast_method = true)
  {
    if(true === $fast_method)
    {
      require_once(sfContext::getInstance()->getConfigCache()->checkConfig('config/bots.yml'));
      $bots = sfConfig::get("bots_bots_bot", array());

      if(count($bots) == 0)
      {
        return false;
      }

      if(1 === preg_match(sprintf("/%s/is", implode($bots, "|")), $this->getUserAgent()))
      {
        return true;
      }
      return false;
    }

    $browscap = new Browscap("/srv/memory_cache/");
    $browser = $browscap->getBrowser();
    return (false === empty($browser->Crawler));
  }
}
