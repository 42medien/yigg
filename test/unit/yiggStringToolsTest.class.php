<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

require_once(dirname(__FILE__).'/../../lib/yiggStringTools.class.php');

$t = new lime_test(2, new lime_output_color());

$t->is(yiggStringTools::utf8_urldecode("http%253A%252F%252Ftest.com%252Ftest"), "http://test.com/test", "Unescapes protocoll");
$t->is(yiggStringTools::utf8_urldecode("%252F%2523sclient%253Dpsy%2526hl%253Dde"), "/#sclient=psy&hl=de", "Unscapes arguments chars");