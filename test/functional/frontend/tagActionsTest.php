<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('1 - Check for empty tags')->
  get('/tags?tags=sfodsijfds8f7ds89f7sdjfbdhs')->

  with('request')->begin()->
    isParameter('module', 'tag')->
    isParameter('action', 'show')->
  end()->

  with('response')->begin()->
    isStatusCode(404)->
  end()
;

$browser->info('2 - Check for proper tags')->
  get('/tags?tags=apple')->

  with('request')->begin()->
    isParameter('module', 'tag')->
    isParameter('action', 'show')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;
