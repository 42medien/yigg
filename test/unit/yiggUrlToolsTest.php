<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../lib/yiggUrlTools.php');

$t = new lime_test( 11, new lime_output_color());

$t->is(
  yiggUrlTools::removeTrackingParameters("http://europa.eu/rapid/pressReleasesAction.do?reference=MEMO/10/232&format=HTML&aged=0&language=EN&guiLanguage=en"),
  "http://europa.eu/rapid/pressReleasesAction.do?reference=MEMO/10/232&format=HTML&aged=0&language=EN&guiLanguage=en"
);


$t->is(
  yiggUrlTools::removeTrackingParameters("http://www.spiegel.de/wirtschaft/soziales/0,1518,697191,00.html"),
  "http://www.spiegel.de/wirtschaft/soziales/0,1518,697191,00.html");

$t->is(
  yiggUrlTools::removeTrackingParameters("http://www.faz.net/s/Rub594835B672714A1DB1A121534F010EE1/Doc~E1EABBD36DED34560998759710C4289DC~ATpl~Ecommon~Scontent.html"),
  "http://www.faz.net/s/Rub594835B672714A1DB1A121534F010EE1/Doc~E1EABBD36DED34560998759710C4289DC~ATpl~Ecommon~Scontent.html");
