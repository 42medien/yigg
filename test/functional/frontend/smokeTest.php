<?php

/**
 * Tests all public views
 */

ini_set('memory_limit', '2G');
ini_set('error_reporting', E_ALL);
include(dirname(__FILE__).'/../../bootstrap/functional.php');

function checkUrl($url, $module, $action, $browser)
{
  $browser->
  get($url)->

  with('request')->begin()->
    isParameter('module', $module)->
    isParameter('action', $action)->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    isValid()->
  end()
;
}


$b = new sfTestFunctional(new sfBrowser());

# Story Lists and story feeds
#checkUrl("/", "story", "bestStories", $b);
checkUrl("/atom/beste-nachrichten", "story", "bestStories", $b);
checkUrl("/atom/beste-nachrichten/heute/politik", "story", "bestStories", $b);

checkUrl("/neueste-nachrichten", "story", "newStories", $b);
checkUrl("/atom/neueste-nachrichten/politik", "story", "newStories", $b);

checkUrl("/nachrichten/button?url=http://www.yigg.de", "story", "externalRate", $b);
checkUrl("/nachrichten/flatbutton?url=http://www.yigg.de", "story", "externalRate", $b);

checkUrl("/nachrichten/archiv/1", "story", "oldStorySupport", $b);

checkUrl("/neu?exturl=http://wknaskdnaslnd.as", "story", "create", $b);

# Story single
checkUrl("/kaputte-welt/youporn-com-private-amateur-videos-im-netz", "story", "show", $b);
checkUrl("/atom/kaputte-welt/youporn-com-private-amateur-videos-im-netz", "story", "show", $b);

#Spy
checkUrl("/spion", "spy", "spy", $b);
checkUrl("/weltspion", "worldspy", "worldSpy", $b);

#User-module
checkUrl("/profil/register", "user", "register", $b);
checkUrl("/profil/rocu", "user", "publicProfile", $b);
checkUrl("/profil/rocu/live-stream", "user", "publicProfile", $b);
checkUrl("/atom/profil/marvin", "user", "publicProfile", $b);


# Misc
checkUrl("/ueber-uns/bugreport", "system", "bugReport", $b);
checkUrl("/search?q=apple", "system", "serveStatic", $b);