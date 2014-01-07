<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('1 - Profile of a non-existing user')->
  get('/profil/ajshdkasdkjasdkasb')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'publicProfile')->
  end()->

  with('response')->begin()->
    isStatusCode(404)->
  end()
;

$browser->info('2 - Profile of a user with dots and dashes')->
  get('/profil/nachrichten-muenchen.de')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'publicProfile')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->info('3 - Normal username')->
  get('/profil/rocu')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'publicProfile')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->info('4 - Normal username atom')->
  get('/atom/profil/marvin')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'publicProfile')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->info('4 - Normal username atom')->
  get('/atom/profil/marvin')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'publicProfile')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->info('Check verify changed with random input')->
  get('/profil/verify_changed_mail/sdfas/safdsfasf')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'verifyChangedEmail')->
    isValid()->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    isValid()->
    checkElement(".error", 1)->
  end()
;

$browser->info('Check verify changed with proper user but with non set config')->
  get('/profil/verify_changed_mail/f63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce/safdsfasf')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'verifyChangedEmail')->
    isValid()->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    isValid()->
    checkElement(".error", 1)->
  end()
;

$user = Doctrine::getTable("User")->findOneByMclientSalt("f63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce");
$user->getConfig()->set("email", "test@test.de", "new_email");
$user->getConfig()->set("secret", "verySecret", "new_email");
$user->save();

$browser->info('Check if it works when everything is all right')->
  get('/profil/verify_changed_mail/f63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce/verySecret')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'verifyChangedEmail')->
    isValid()->
  end()->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'login')->
    isValid()->
  end();

$browser->test()->is(Doctrine::getTable("User")->findOneById($user->id)->email, "test@test.de", "Email was changed");

$browser->info('Check a second time. Now it should be an error again.')->
  get('/profil/verify_changed_mail/f63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce/verySecret')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'verifyChangedEmail')->
    isValid()->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    isValid()->
    checkElement(".error", 1)->
  end();

