<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('1 - Beste Nachrichten Today')->
  get('/')->

  with('request')->begin()->
    isParameter('module', 'story')->
    isParameter('action', 'bestStories')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.hentry', 15)->
    checkElement('.hfeed .video')->
    checkElement('#sponsoring_1')->
  end()
;

$browser->info('2 - Beste Nachrichten Since Yesterday')->
  get('/beste-nachrichten/gestern')->

  with('request')->begin()->
    isParameter('module', 'story')->
    isParameter('action', 'bestStories')->
    isParameter('filter', 'gestern')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.hentry', 15)->
    checkElement('#sponsoring_1')->
  end()
;

$browser->info('3 - Neueste Nachrichten')->
  get('/neueste-nachrichten')->

  with('request')->begin()->
    isParameter('module', 'story')->
    isParameter('action', 'newStories')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#sponsoring_2')->
  end()
;

$browser->info("AS a anonymous-user I clicked on an external bookmarklet")->
  get('/neu?exturl=http%253A%252F%252Ftest.com%252F')->

  info("GIVEN it was done on a whitelisted page")->

  info('THEN the story creation page is displayed')->
  with('request')->begin()->
    isParameter('module', 'story')->
    isParameter('action', 'create')->
  end()->

  info("AND it shows the url prefilled in the external_url field")->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value="http://test.com/"]', 1)->
  end()
;