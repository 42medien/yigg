<?php
ini_set('memory_limit','64M');
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->info("Check proper login")->
  get('/login')->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'login')->
  end()->

  with('response')->begin()->
    checkForm(new FormUserLogin())->
    isStatusCode(401)->
    isValid()->
  end()->

  click("#LoginForm .button", array("LoginForm" => array("username" => "rocu", "password" => "b4%CJaR73A2wfEes")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isValid()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'user')->
    isParameter('action', 'myYigg')->
  end();


$browser->info("Check proper login for anonymous")->
  get('/login')->

  click("#LoginForm .button", array("LoginForm" => array("username" => "testertest", "password" => "testertest")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isValid()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'story')->
    isParameter('action', 'bestStories')->
  end();

$browser->info("Check wrong password")->
  get('/login')->
  click("#LoginForm .button", array("LoginForm" => array("username" => "rocu", "password" => "asdda")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isValid()->
    isStatusCode(401)->
    checkElement('p.error', "/Benutzername oder Passwort falsch/")->
  end();

$browser->info("Check wrong username")->
  get('/login')->
  click("#LoginForm .button", array("LoginForm" => array("username" => "asdas", "password" => "asdda")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isValid()->
    isStatusCode(401)->
    checkElement('p.error', "/Benutzername oder Passwort falsch/")->
  end();

$browser->info("Check non-activated  user")->
  get('/login')->
  click("#LoginForm .button", array("LoginForm" => array("username" => "hunterinhoho", "password" => "dsfdsf")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isValid()->
    isStatusCode(401)->
    checkElement('p.error', "/Dein Account wurde noch nicht freigeschaltet/")->
  end();

$browser = new sfTestFunctional(new sfBrowser());
$browser->info("Check login from frontpage")->
  get('/')->
  click(".userinfo .button", array("LoginForm" => array("username" => "rocu", "password" => "b4%CJaR73A2wfEes")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('response')->begin()->
    isModule("story")->
    isAction("bestStories")->
  end();

$browser = new sfTestFunctional(new sfBrowser());
$browser->info("Check false login from frontpage")->
  get('/')->
  click(".userinfo .button", array("LoginForm" => array("username" => "rocu", "password" => "dasdas")))->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isStatusCode(401)->
    isModule("user")->
    isAction("login")->
  end();
