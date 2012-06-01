<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('1 - Tweet a comment')->
  get('/kommentare/tweet/story/2236770/132632')->
 
  with('request')->begin()->
    isParameter('module', 'comment')->
    isParameter('action', 'tweet')->
  end()->
 
  with('response')->begin()->
    isRedirected()->
  end()
;