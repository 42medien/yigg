
<?php
include(dirname(__FILE__).'/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);