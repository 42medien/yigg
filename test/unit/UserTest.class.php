<?php
define("SECONDS_IN_YEAR", 365*24*60*60);
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$t->info("Test User::getAge");
  $u = new User();
  $u->getConfig()->set("birthday", date("Y-m-d H:i:s", time()), "profile");
  $t->is($u->getAge(), 0, "Current date returns 0");

  $u = new User();
  $u->getConfig()->set("birthday", date("Y-m-d H:i:s", time() - SECONDS_IN_YEAR - 1), "profile");
  $t->is($u->getAge(), 1, "Date 1 YEAR and 1 second ago returns 1");

  $u = new User();
  $u->getConfig()->set("birthday", date("Y-m-d H:i:s", time() - SECONDS_IN_YEAR + 86400), "profile");
  $t->is($u->getAge(), 0, "1 day to go to 1 year returns 0");