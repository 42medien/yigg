<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('1 - Normal search')->
  get('/search?q=yigg+button')->
 
  with('request')->begin()->
    isParameter('module', 'search')->
    isParameter('action', 'search')->
  end()->
 
  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->info('2 - search with many tags')->
  get('/search?q=yigg+button+gina+lisa+youporn+bla+foo+many+tags+damned+apple')->
 
  with('request')->begin()->
    isParameter('module', 'search')->
    isParameter('action', 'search')->
  end()->
 
  with('response')->begin()->
    isStatusCode(200)->
  end()
;