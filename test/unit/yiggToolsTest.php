<?php

include(dirname(__FILE__).'/../bootstrap/Doctrine.php');
require_once(dirname(__FILE__).'/../../lib/yiggTools.class.php');

$t = new lime_test( 12, new lime_output_color());

$t->diag("yiggTools::convertToRealDate()");

$t->diag("yiggTools::removeQuotedContent()");
$t->is(yiggTools::removeQuotedContent("[quote]@rocu das ist ein test[/quote]"), "");
$t->is(yiggTools::removeQuotedContent("Lorem Ipsum dolor"), "Lorem Ipsum dolor");
$t->is(yiggTools::removeQuotedContent("[quote] @rocu das ist ein test [/quote] @simon [quote] @kmr das ist ein test [/quote]"), " @simon ");
$t->is(yiggTools::removeQuotedContent("[quote=simon] @rocu das ist ein test [/quote] @simon"), " @simon");
$t->is(yiggTools::removeQuotedContent("This is a normal text without quotes [b]And shall not be touched at all[/b]"), "This is a normal text without quotes [b]And shall not be touched at all[/b]");

$t->diag("yiggTools::getAdressedUsernames()");
$t->is(yiggTools::getAdressedUsernames("@rocu @normalguy @kmr Test Lorem ipsum dolor"), array("rocu", "normalguy", "kmr"));
$t->is(yiggTools::getAdressedUsernames("Lorem Ipsum Dolor"), array());

$t->info("yiggTools::linkUrls");
$t->is(yiggTools::linkUrlsWithShortUrls("lorem ipsum"), "lorem ipsum", "Does not alter normal text");
$t->is(yiggTools::linkUrlsWithShortUrls("http://www.rocu.de"), '<a href="http://yigg.it/a">www.rocu.de</a>', "Looks up yigg.it link for existing url");


$t->is(yiggTools::linkUrlsWithShortUrls("http://www.rocu.de Test"), '<a href="http://yigg.it/a">www.rocu.de</a> Test', "Ignores spaces after url");

$t->is(yiggTools::linkUrlsWithShortUrls("http://www.rocu.de

Test"), '<a href="http://yigg.it/a">www.rocu.de</a>

Test', "Works with linebreak");


$t->is(yiggTools::linkUrlsWithShortUrls('
http://www.rocu.de'),
  '
<a href="http://yigg.it/a">www.rocu.de</a>', "Works after newline and preserves newline");

$t->info("yiggTools::findUrlsInString");
$t->is_deeply(yiggTools::findUrlsInString("<p>http://www.rocu.de"), array("http://www.rocu.de"), "Looks up yigg.it link for existing url");




?>